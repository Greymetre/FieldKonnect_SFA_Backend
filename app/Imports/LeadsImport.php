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

            // Rows sharing a Lead ID belong to one lead: reuse it (also across
            // earlier chunks/imports) and only attach the extra contact.
            $lead = $this->existingLead($externalLeadId, $row['id'] ?? null);

            if (! $lead) {
                $lead = $this->createLead($row, $externalLeadId);
                if ($externalLeadId !== null) {
                    $this->leadCache['L:'.$externalLeadId] = $lead;
                }

                if ($lead->assign_to) {
                    $assignedCounts[$lead->assign_to] = ($assignedCounts[$lead->assign_to] ?? 0) + 1;
                }
            } else {
                // Existing lead: only the assignee is updated from the sheet, so a
                // re-import can split / move leads between users.
                $assigneeId = $this->resolveAssigneeId($row['assignee'] ?? null);
                if ($assigneeId && (int) $lead->assign_to !== $assigneeId) {
                    $lead->update(['assign_to' => $assigneeId]);
                    $reassignedCounts[$assigneeId] = ($reassignedCounts[$assigneeId] ?? 0) + 1;
                }
            }

            $this->upsertContact($lead, $row, $externalContactId);

            if (!empty($row['note'])) {
                LeadNote::firstOrCreate([
                    'note' => $row['note'],
                    'lead_id' => $lead->id,
                ], [
                    'created_by' => Auth::id(),
                ]);
            }
        }

        foreach ($assignedCounts as $userId => $count) {
            SendPushNotification($userId, '🟢 You have been assigned ' . $count . ' new leads.');
            StoreLeadNotification(null, 'Assigned Lead', '🟢 You have been assigned ' . $count . ' new leads.', $userId);
        }
        foreach ($reassignedCounts as $userId => $count) {
            SendPushNotification($userId, '🟢 ' . $count . ' leads have been assigned to you.');
            StoreLeadNotification(null, 'Assigned Lead', '🟢 ' . $count . ' leads have been assigned to you.', $userId);
        }
    }

    /**
     * Find the lead a row belongs to: by Lead ID, or - for sheets exported from
     * this system that have no Lead ID - by the internal "ID" column.
     */
    private function existingLead(?string $externalLeadId, $internalId): ?Lead
    {
        $internalId = $this->normalizeExternalId($internalId);
        $key = $externalLeadId !== null ? 'L:'.$externalLeadId : ($internalId !== null && ctype_digit($internalId) ? 'I:'.$internalId : null);
        if ($key === null) {
            return null;
        }
        // A lead's rows usually come one after another; look it up once.
        if (isset($this->leadCache[$key])) {
            return $this->leadCache[$key];
        }

        $lead = $externalLeadId !== null
            ? Lead::where('external_lead_id', $externalLeadId)->first()
            : Lead::find((int) $internalId);

        return $lead ? $this->leadCache[$key] = $lead : null;
    }

    /** @var array<string, Lead> */
    private array $leadCache = [];

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
        $status = !empty($row['lead_type'])
            ? Status::where('display_name', $row['lead_type'])->value('id')
            : null;
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

    private function upsertContact(Lead $lead, $row, ?string $externalContactId): void
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
            $contact->update($updates);
            return;
        }

        LeadContact::create($data + [
            'lead_id' => $lead->id,
            'external_contact_id' => $externalContactId,
            'created_by' => Auth::id(),
        ]);
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
            'lead_type' => 'nullable|exists:statuses,display_name',
            'lead_source' => 'nullable|in:Google,Indiamart,Justdial,Instagram,Facebook,LinkedIn,Self',
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

            'lead_type.exists' => 'The lead type must be a valid status name.',
            'lead_source.in' => 'The lead source must be one of: Google, Indiamart, Justdial, Instagram, Facebook, LinkedIn, Self.',
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
