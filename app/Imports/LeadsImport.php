<?php

namespace App\Imports;

use App\Models\Address;
use App\Models\City;
use App\Models\District;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\LeadNote;
use App\Models\Pincode;
use App\Models\State;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class LeadsImport implements ToCollection, WithValidation, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsFailures;

    private int $importBatchOrder;

    private int $importRowOrder = 0;

    /** Totals shown to the user after the upload. */
    public int $createdLeads = 0;
    public int $reassignedLeads = 0;
    public int $updatedLeads = 0;
    public int $unchangedLeads = 0;

    public function __construct()
    {
        $this->importBatchOrder = (int) round(microtime(true) * 1000000);
    }

    public function collection(Collection $rows)
    {
        // New / re-assigned leads per assignee in this chunk (for notifications).
        $assignedCounts = [];
        $reassignedCounts = [];

        foreach ($rows as $row) {
            // Do not create an empty lead for blank/spacer rows in the sheet.
            if ($row->filter(fn ($value) => $value !== null && $value !== '')->isEmpty()) {
                continue;
            }

            $externalLeadId = $this->normalizeExternalId($row['lead_id'] ?? null);
            $externalContactId = $this->normalizeExternalId($row['contact_id'] ?? null);

            // Existing lead: by the system ID (first "ID" column of the Leads
            // export), else by Lead ID. Rows of the same lead only add contacts.
            $lead = $this->existingLead($externalLeadId, $row['id'] ?? null);

            if (! $lead) {
                $lead = $this->createLead($row, $externalLeadId);
                $this->createdLeads++;
                // Later rows of this lead (same Lead ID / same sheet ID) reuse it.
                if ($externalLeadId !== null) {
                    $this->leadCache['L:'.$externalLeadId] = $lead;
                }
                $sheetId = $this->normalizeExternalId($row['id'] ?? null);
                if ($sheetId !== null && ctype_digit($sheetId)) {
                    $this->leadCache['I:'.$sheetId] = $lead;
                }

                if ($lead->assign_to) {
                    $assignedCounts[$lead->assign_to] = ($assignedCounts[$lead->assign_to] ?? 0) + 1;
                }
            } else {
                // Existing lead (same Lead ID): every filled cell that differs
                // updates the lead; blank cells keep what is already saved.
                $assigneeId = $this->resolveAssigneeId($row['assignee'] ?? null);
                $isReassigned = $assigneeId && (int) $lead->assign_to !== $assigneeId;
                $changed = $this->updateLead($lead, $row, $assigneeId, $externalLeadId);
                if ($isReassigned) {
                    $this->reassignedLeads++;
                    $reassignedCounts[$assigneeId] = ($reassignedCounts[$assigneeId] ?? 0) + 1;
                }
                if ($changed) {
                    $this->changedLeads[$lead->id] = true;
                }
                $this->touchedLeads[$lead->id] = true;
            }

            if ($this->upsertContact($lead, $row, $externalContactId) && isset($this->touchedLeads[$lead->id])) {
                $this->changedLeads[$lead->id] = true;
            }

            if (!empty($row['note'])) {
                LeadNote::firstOrCreate([
                    'note' => $row['note'],
                    'lead_id' => $lead->id,
                ], [
                    'created_by' => Auth::id(),
                ]);
            }
        }

        $this->updatedLeads = count($this->changedLeads);
        $this->unchangedLeads = count($this->touchedLeads) - $this->updatedLeads;

        foreach ($assignedCounts as $userId => $count) {
            SendPushNotification($userId, '🟢 You have been assigned ' . $count . ' new leads.', 'lead', null, 'Assigned Lead');
            StoreLeadNotification(null, 'Assigned Lead', '🟢 You have been assigned ' . $count . ' new leads.', $userId);
        }
        foreach ($reassignedCounts as $userId => $count) {
            SendPushNotification($userId, '🟢 ' . $count . ' leads have been assigned to you.', 'lead', null, 'Assigned Lead');
            StoreLeadNotification(null, 'Assigned Lead', '🟢 ' . $count . ' leads have been assigned to you.', $userId);
        }
    }

    /**
     * Find the lead a row belongs to: first by the system-generated "ID" column
     * (leads.id, first column of the Leads export), then by Lead ID.
     */
    private function existingLead(?string $externalLeadId, $internalId): ?Lead
    {
        $internalId = $this->normalizeExternalId($internalId);

        // A lead's rows usually come one after another; look each key up once.
        if ($internalId !== null && ctype_digit($internalId)) {
            $key = 'I:'.$internalId;
            if (! array_key_exists($key, $this->leadCache)) {
                $this->leadCache[$key] = Lead::find((int) $internalId);
            }
            if ($this->leadCache[$key]) {
                return $this->leadCache[$key];
            }
        }

        if ($externalLeadId !== null) {
            $key = 'L:'.$externalLeadId;
            if (! array_key_exists($key, $this->leadCache)) {
                $this->leadCache[$key] = Lead::where('external_lead_id', $externalLeadId)->first();
            }

            return $this->leadCache[$key];
        }

        return null;
    }

    /** @var array<string, Lead|null> */
    private array $leadCache = [];

    /** @var array<int, bool> existing leads seen in this import */
    private array $touchedLeads = [];

    /** @var array<int, bool> existing leads where something actually changed */
    private array $changedLeads = [];

    /**
     * Update an existing lead and its address from the sheet. Only filled cells
     * are applied, so a sheet with fewer columns never wipes saved data.
     * Returns true when anything changed.
     */
    private function updateLead(Lead $lead, $row, ?int $assigneeId, ?string $externalLeadId): bool
    {
        $filled = fn ($key) => $this->filled($row[$key] ?? null);

        $updates = [];
        // Matched by system ID: the sheet's Lead ID may be new/changed (kept unique).
        if ($externalLeadId !== null && $externalLeadId !== (string) $lead->external_lead_id
            && ! Lead::where('external_lead_id', $externalLeadId)->whereKeyNot($lead->id)->exists()) {
            $updates['external_lead_id'] = $externalLeadId;
        }
        if ($filled('firm_name')) $updates['company_name'] = trim((string) $row['firm_name']);
        if ($filled('website')) $updates['company_url'] = trim((string) $row['website']);
        if ($filled('lead_source')) $updates['lead_source'] = $row['lead_source'];
        if ($filled('lead_type') && ($statusId = $this->leadStatusId($row['lead_type']))) $updates['status'] = $statusId;
        if ($filled('lead_generation_date') && ($date = $this->parseDate($row['lead_generation_date']))) $updates['lead_generation_date'] = $date;
        if ($filled('alternet_number')) $updates['alternate_number'] = (string) $row['alternet_number'];
        if ($filled('revenue_rs_cr')) $updates['revenue_rs_cr'] = $row['revenue_rs_cr'];
        foreach (['others_1', 'others_2', 'others_3', 'others_4', 'others_5'] as $key) {
            if ($filled($key)) $updates[$key] = $row[$key];
        }
        if ($assigneeId) $updates['assign_to'] = $assigneeId;

        $lead->fill($updates);
        $changed = $lead->isDirty();
        if ($changed) {
            $lead->save();
        }

        // Address
        $addressUpdates = [];
        if ($filled('address')) $addressUpdates['address1'] = trim((string) $row['address']);
        if ($filled('place')) $addressUpdates['address2'] = trim((string) $row['place']);
        if ($filled('pincode') && ($id = Pincode::where('pincode', $row['pincode'])->value('id'))) $addressUpdates['pincode_id'] = $id;
        if ($filled('state') && ($id = State::where('state_name', $row['state'])->value('id'))) $addressUpdates['state_id'] = $id;
        if ($filled('city') && ($id = City::where('city_name', $row['city'])->value('id'))) $addressUpdates['city_id'] = $id;
        if ($filled('district') && ($id = District::where('district_name', $row['district'])->value('id'))) $addressUpdates['district_id'] = $id;
        if ($addressUpdates) {
            $address = Address::where('model_type', 'App\Models\Lead')->where('model_id', $lead->id)->first();
            if ($address) {
                $address->fill($addressUpdates);
                if ($address->isDirty()) {
                    $address->save();
                    $changed = true;
                }
            } else {
                Address::create($addressUpdates + [
                    'model_type' => 'App\Models\Lead',
                    'model_id' => $lead->id,
                    'country_id' => 1,
                    'created_by' => Auth::id(),
                ]);
                $changed = true;
            }
        }

        return $changed;
    }

    /** A cell with real content (not blank / "N/A" / "-"). */
    private function filled($value): bool
    {
        if ($value === null) {
            return false;
        }
        $value = trim((string) $value);

        return $value !== '' && ! in_array(strtoupper($value), ['N/A', 'NA', '-'], true);
    }

    /** Excel serial number or a date text (e.g. 26-11-2026) → Y-m-d. */
    private function parseDate($value): ?string
    {
        try {
            if (is_numeric($value)) {
                return Carbon::create(1899, 12, 30)->addDays((int) $value)->toDateString();
            }

            return Carbon::parse(str_replace('/', '-', trim((string) $value)))->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** @var array<string, int|null> */
    private array $leadStatusCache = [];

    /** Lead type by its display name or internal name, any case (export writes either). */
    private function leadStatusId($value): ?int
    {
        $key = strtolower(trim((string) $value));
        if ($key === '') {
            return null;
        }

        return $this->leadStatusCache[$key] ??= Status::where('module', 'LeadStatus')
            ->where(fn ($query) => $query->whereRaw('LOWER(TRIM(display_name)) = ?', [$key])->orWhereRaw('LOWER(TRIM(status_name)) = ?', [$key]))
            ->value('id');
    }

    /** "linkedin", " Linked In ", "INDIA MART" → the canonical lead source, else null. */
    private function canonicalLeadSource($value): ?string
    {
        $key = preg_replace('/[^a-z]/', '', strtolower((string) $value));
        if ($key === '') {
            return null;
        }
        foreach (array_keys(config('constants.LEAD_SOURCES', [])) as $source) {
            if (preg_replace('/[^a-z]/', '', strtolower($source)) === $key) {
                return $source;
            }
        }

        return null;
    }

    /**
     * Clean a row before validation (Maatwebsite calls this; collection() gets
     * the cleaned row too): lead source / lead type in any case are accepted.
     * For a lead that already exists, a value we don't know is ignored (the
     * saved value is kept) instead of stopping the whole import.
     */
    public function prepareForValidation($data, $index)
    {
        $exists = $this->existingLead(
            $this->normalizeExternalId($data['lead_id'] ?? null),
            $data['id'] ?? null
        ) !== null;

        if ($this->filled($data['lead_source'] ?? null)) {
            $source = $this->canonicalLeadSource($data['lead_source']);
            $data['lead_source'] = $source ?? ($exists ? null : trim((string) $data['lead_source']));
        } else {
            $data['lead_source'] = null;
        }

        if ($this->filled($data['lead_type'] ?? null)) {
            $statusId = $this->leadStatusId($data['lead_type']);
            $data['lead_type'] = $statusId
                ? Status::whereKey($statusId)->value('display_name')
                : ($exists ? null : trim((string) $data['lead_type']));
        } else {
            $data['lead_type'] = null;
        }

        return $data;
    }

    private function createLead($row, ?string $externalLeadId): Lead
    {
        $leadGenerationDate = $row['lead_generation_date'] ?? null;
        if (is_numeric($leadGenerationDate)) {
            $excelDate = $leadGenerationDate - 25569; // Adjust for Excel's epoch
            $unixTimestamp = strtotime('+' . $excelDate . ' days', strtotime('1970-01-01'));
            $leadGenerationDate = Carbon::createFromTimestamp($unixTimestamp)->toDateString();
        } elseif (!empty($leadGenerationDate)) {
            $leadGenerationDate = Carbon::parse($leadGenerationDate)->toDateString();
        }
        $status = !empty($row['lead_type']) ? $this->leadStatusId($row['lead_type']) : null;
        $expectedKeys = [
            'id',
            'lead_id',
            'contact_id',
            'lead_generation_date',
            'firm_name',
            'customer_name',
            'customer_number',
            'email',
            'contact_url',
            'lead_source',
            'pincode',
            'place',
            'city',
            'district',
            'state',
            'address',
            'lead_type',
            'assignee',
            'note',
            'website',
            'designation',
            'alternet_number',
            'revenue_rs_cr',
            'others_1',
            'others_2',
            'others_3',
            'others_4',
            'others_5',
            // Columns of the Leads export that are not lead data.
            'on_location', 'lead_status', 'close_duration', 'created_by', 'lead_time', 'sales_value', 'task',
            'note_1', 'note_1_date', 'note_2', 'note_2_date', 'note_3', 'note_3_date', 'note_4', 'note_4_date', 'note_5', 'note_5_date',
        ];

        // Collect other (unexpected) keys without null/empty key or value
        $otherData = collect($row)->filter(function ($value, $key) use ($expectedKeys) {
            return !in_array($key, $expectedKeys) && !is_null($key) && $key !== '' && !is_null($value) && $value !== '';
        })->toArray();

        $otherData = !empty($otherData)
            ? json_encode($otherData, JSON_UNESCAPED_UNICODE)
            : null;

        $lead = Lead::create([
            'external_lead_id' => $externalLeadId,
            'company_name' => $row['firm_name'] ?? null,
            'company_url' => $row['website'] ?? null,
            'status' => $status ?? 0,
            'created_by' => Auth::id(),
            // Blank date in the sheet = imported today (same as creating a lead manually).
            'lead_generation_date' => $leadGenerationDate ?: now()->toDateString(),
            'lead_source' => $row['lead_source'] ?? null,
            'assign_to' => $this->resolveAssigneeId($row['assignee'] ?? null),
            'alternate_number' => isset($row['alternet_number']) ? (string) $row['alternet_number'] : null,
            'revenue_rs_cr' => $row['revenue_rs_cr'] ?? null,
            'others_1' => $row['others_1'] ?? null,
            'others_2' => $row['others_2'] ?? null,
            'others_3' => $row['others_3'] ?? null,
            'others_4' => $row['others_4'] ?? null,
            'others_5' => $row['others_5'] ?? null,
            'others' => $otherData,
            'import_batch_order' => $this->importBatchOrder,
            'import_row_order' => ++$this->importRowOrder,
        ]);

        Address::create([
            'model_type' => 'App\Models\Lead',
            'model_id' => $lead->id,
            'address1' => $row['address'] ?? '',
            'address2' => $row['place'] ?? '',
            'country_id' => 1,
            'pincode_id' => !empty($row['pincode']) ? Pincode::where('pincode', $row['pincode'])->value('id') : null,
            'state_id' => !empty($row['state']) ? State::where('state_name', $row['state'])->value('id') : null,
            'city_id' => !empty($row['city']) ? City::where('city_name', $row['city'])->value('id') : null,
            'district_id' => !empty($row['district']) ? District::where('district_name', $row['district'])->value('id') : null,
            'created_by' => Auth::id()
        ]);

        return $lead;
    }

    /** Returns true when a contact was added or changed. */
    private function upsertContact(Lead $lead, $row, ?string $externalContactId): bool
    {
        $data = [
            'name' => $row['customer_name'] ?? null,
            'title' => $row['designation'] ?? null,
            'phone_number' => isset($row['customer_number']) ? (string) $row['customer_number'] : null,
            'email' => $row['email'] ?? null,
            'url' => $row['contact_url'] ?? null,
            'lead_source' => $row['lead_source'] ?? null,
        ];

        // Same contact on the same lead (by Contact ID, else by phone number)
        // is updated instead of duplicated.
        $contact = null;
        if ($externalContactId !== null) {
            $contact = LeadContact::where('lead_id', $lead->id)
                ->where('external_contact_id', $externalContactId)
                ->first();
        }
        $phone = substr(preg_replace('/\D+/', '', (string) $data['phone_number']), -10);
        if (! $contact && strlen($phone) === 10) {
            $contact = LeadContact::where('lead_id', $lead->id)->get()
                ->first(fn ($existing) => substr(preg_replace('/\D+/', '', (string) $existing->phone_number), -10) === $phone);
        }

        if ($contact) {
            // Blank cells must not wipe data already saved on the contact.
            $updates = array_filter($data, fn ($value) => $value !== null && trim((string) $value) !== '' && strtoupper(trim((string) $value)) !== 'N/A');
            if ($externalContactId !== null && ! $contact->external_contact_id) {
                $updates['external_contact_id'] = $externalContactId;
            }
            $contact->fill($updates);
            if (! $contact->isDirty()) {
                return false;
            }
            $contact->save();
            return true;
        }

        LeadContact::create($data + [
            'lead_id' => $lead->id,
            'external_contact_id' => $externalContactId,
            'created_by' => Auth::id(),
        ]);

        return true;
    }

    /** @var array<string, int|null> */
    private array $assigneeCache = [];

    // Assignee column accepts either the user's name or email (cached per import).
    private function resolveAssigneeId($value): ?int
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        return $this->assigneeCache[$value] ??= (User::where('email', $value)->value('id')
            ?? User::where('name', $value)->value('id'));
    }

    // Excel returns numeric IDs as int/float (e.g. 101.0); store them as clean strings.
    private function normalizeExternalId($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_float($value) && floor($value) == $value) {
            $value = (string) (int) $value;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    public function rules(): array
    {
        return [
            'lead_id' => 'nullable',
            'contact_id' => 'nullable',
            'lead_generation_date' => 'nullable',
            'firm_name' => 'required',
            'customer_name' => 'required',
            'designation' => 'nullable',
            'customer_number' => 'nullable',
            'alternet_number' => 'nullable',
            'revenue_rs_cr' => 'nullable',
            'email' => 'nullable',
            'contact_url' => 'nullable',
            'pincode' => 'nullable|exists:pincodes,pincode',
            'city' => 'nullable|exists:cities,city_name',
            'district' => 'nullable|exists:districts,district_name',
            'state' => 'nullable|exists:states,state_name',
            'lead_type' => ['nullable', \Illuminate\Validation\Rule::exists('statuses', 'display_name')->where('module', 'LeadStatus')],
            'lead_source' => ['nullable', \Illuminate\Validation\Rule::in(array_keys(config('constants.LEAD_SOURCES', [])))],
            'assignee' => ['nullable', function ($attribute, $value, $fail) {
                if (! $this->resolveAssigneeId($value)) {
                    $fail('The selected assignee name/email was not found in users.');
                }
            }],
            'place' => 'nullable',
            'address' => 'nullable',
            'note' => 'nullable',
            'website' => 'nullable',
            'others_1' => 'nullable',
            'others_2' => 'nullable',
            'others_3' => 'nullable',
            'others_4' => 'nullable',
            'others_5' => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'firm_name.required' => 'Firm name is required.',
            'customer_name.required' => 'Customer name is required.',
            'pincode.exists' => 'The selected pincode is not valid.',
            'city.exists' => 'The selected city does not exist in our records.',
            'district.exists' => 'The selected district does not exist in our records.',
            'state.exists' => 'The selected state does not exist in our records.',

            'lead_type.exists' => 'The lead type ":input" is not valid. Use one of the lead types shown in the Lead Type filter.',
            'lead_source.in' => 'The lead source ":input" is not valid. It must be one of: '.implode(', ', array_keys(config('constants.LEAD_SOURCES', []))).'.',
        ];
    }


    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function onFailure(Failure ...$failures)
    {
        Log::stack(['import-failure-logs'])->info(json_encode($failures));
    }
}
