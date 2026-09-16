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


    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Do not create an empty lead for blank/spacer rows in the sheet.
            if ($row->filter(fn ($value) => $value !== null && $value !== '')->isEmpty()) {
                continue;
            }

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
                'lead_generation_date',
                'firm_name',
                'customer_name',
                'customer_number',
                'email',
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

            // Step 1: Collect other (unexpected) keys without null/empty key or value
            $otherData = collect($row)->filter(function ($value, $key) use ($expectedKeys) {
                return !in_array($key, $expectedKeys) && !is_null($key) && $key !== '' && !is_null($value) && $value !== '';
            })->toArray();

            $otherData = !empty($otherData)
                ? json_encode($otherData, JSON_UNESCAPED_UNICODE)
                : null;
            $lead = Lead::create([
                'company_name' => $row['firm_name'] ?? null,
                'company_url' => $row['website'] ?? null,
                'status' => $status ?? 0,
                'created_by' => Auth::id(),
                'lead_generation_date' => $leadGenerationDate ?: null,
                'lead_source' => $row['lead_source'] ?? null,
                'assign_to' => !empty($row['assignee'])
                    ? User::where('name', $row['assignee'])->value('id')
                    : null,
                'alternate_number' => isset($row['alternet_number']) ? (string) $row['alternet_number'] : null,
                'revenue_rs_cr' => $row['revenue_rs_cr'] ?? null,
                'others_1' => $row['others_1'] ?? null,
                'others_2' => $row['others_2'] ?? null,
                'others_3' => $row['others_3'] ?? null,
                'others_4' => $row['others_4'] ?? null,
                'others_5' => $row['others_5'] ?? null,
                'others' => $otherData,
            ]);
            if ($lead->id) {
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
                LeadContact::create([
                    'name' => $row['customer_name'] ?? null,
                    'title' => $row['designation'] ?? null,
                    'phone_number' => isset($row['customer_number']) ? (string) $row['customer_number'] : null,
                    'email' => $row['email'] ?? null,
                    'lead_source' => $row['lead_source'] ?? null,
                    'lead_id' => $lead->id,
                    'created_by' => Auth::id()
                ]);
                if (isset($row['note']) && !empty($row['note'])) {
                    $note = LeadNote::create([
                        'note' => $row['note'],
                        'lead_id' => $lead->id,
                        'created_by' => Auth::id()
                    ]);
                }
            }
        }
        $assignees = $rows->pluck('assignee')->countBy()->toArray();
        foreach ($assignees as $assignee => $count) {
            $user = User::where('name', $assignee)->first();
            if (!empty($user)) {
                SendPushNotification($user->id, '🟢 You have been assigned ' . $count . ' new leads.');
                StoreLeadNotification(null, 'Assigned Lead', '🟢 You have been assigned ' . $count . ' new leads.', $user->id);
            }
        }
    }

    public function rules(): array
    {
        return [
            'lead_generation_date' => 'nullable',
            'firm_name' => 'required',
            'customer_name' => 'required',
            'designation' => 'nullable',
            'customer_number' => 'nullable',
            'alternet_number' => 'nullable',
            'revenue_rs_cr' => 'nullable',
            'email' => 'nullable',
            'pincode' => 'nullable|exists:pincodes,pincode',
            'city' => 'nullable|exists:cities,city_name',
            'district' => 'nullable|exists:districts,district_name',
            'state' => 'nullable|exists:states,state_name',
            'lead_type' => 'nullable|exists:statuses,display_name',
            'lead_source' => 'nullable|in:Google,Indiamart,Justdial,Instagram,Facebook,LinkedIn,Self',
            'assignee' => 'nullable|exists:users,name',
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
            'assignee.exists' => 'The selected assignee name was not found in users.',
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
