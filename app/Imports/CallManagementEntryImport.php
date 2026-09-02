<?php

namespace App\Imports;

use App\Models\CallManagementEntry;
use App\Models\Pincode;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CallManagementEntryImport implements ToCollection, WithHeadingRow
{
    private int $createdBy;
    private int $imported = 0;

    public function __construct(int $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                if ($row->filter()->isEmpty()) {
                    continue;
                }

                $data = $row->toArray();
                $data['mobile_number'] = preg_replace('/\D/', '', (string) ($data['mobile_number'] ?? ''));
                $data['pincode'] = trim((string) ($data['pincode'] ?? ''));
                $data['caller_email'] = trim((string) ($data['caller_email'] ?? ''));

                $validator = Validator::make($data, [
                    'firm_name' => ['required', 'string', 'max:200'],
                    'contact_person_name' => ['required', 'string', 'max:200'],
                    'mobile_number' => ['required', 'digits:10'],
                    'customer_type' => ['nullable', 'string', 'max:100'],
                    'address' => ['nullable', 'string', 'max:500'],
                    'pincode' => ['required'],
                    'caller_email' => ['required', 'email'],
                    'custom_column_1' => ['nullable', 'string', 'max:255'],
                    'custom_column_2' => ['nullable', 'string', 'max:255'],
                    'custom_column_3' => ['nullable', 'string', 'max:255'],
                    'custom_column_4' => ['nullable', 'string', 'max:255'],
                ]);

                if ($validator->fails()) {
                    throw ValidationException::withMessages([
                        'import_file' => 'Row '.($index + 2).': '.$validator->errors()->first(),
                    ]);
                }

                $pincode = Pincode::with(['cityname.districtname.statename', 'cityname.statename'])
                    ->where('active', 'Y')
                    ->where('pincode', $data['pincode'])
                    ->first();
                $caller = User::permission('call_management_access')
                    ->where('active', 'Y')
                    ->where('email', $data['caller_email'])
                    ->first();

                if (! $pincode) {
                    throw ValidationException::withMessages(['import_file' => 'Row '.($index + 2).': Pincode not found in the system.']);
                }
                if (! $caller) {
                    throw ValidationException::withMessages(['import_file' => 'Row '.($index + 2).': Caller email is not an active call-management user.']);
                }

                $city = $pincode->cityname;
                $district = optional($city)->districtname;
                $state = optional($district)->statename ?: optional($city)->statename;

                CallManagementEntry::create([
                    'firm_name' => $data['firm_name'],
                    'contact_person_name' => $data['contact_person_name'],
                    'mobile_number' => $data['mobile_number'],
                    'customer_type' => $data['customer_type'] ?? null,
                    'address' => $data['address'] ?? null,
                    'pincode_id' => $pincode->id,
                    'pincode' => $pincode->pincode,
                    'city' => optional($city)->city_name,
                    'district' => optional($district)->district_name,
                    'state' => optional($state)->state_name,
                    'assigned_user_id' => $caller->id,
                    'custom_column_1' => $data['custom_column_1'] ?? null,
                    'custom_column_2' => $data['custom_column_2'] ?? null,
                    'custom_column_3' => $data['custom_column_3'] ?? null,
                    'custom_column_4' => $data['custom_column_4'] ?? null,
                    'status' => 'pending',
                    'created_by' => $this->createdBy,
                ]);
                $this->imported++;
            }
        });
    }

    public function importedCount(): int
    {
        return $this->imported;
    }
}
