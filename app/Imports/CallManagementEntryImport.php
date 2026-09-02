<?php

namespace App\Imports;

use App\Models\CallManagementEntry;
use App\Models\Pincode;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CallManagementEntryImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private int $createdBy;
    private int $created = 0;
    private int $updated = 0;
    private array $errors = [];

    public function __construct(int $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) {
                continue;
            }

            try {
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
                    'caller_email' => ['nullable', 'email'],
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

                $entry = CallManagementEntry::where('mobile_number', $data['mobile_number'])->first();

                $pincode = Pincode::with(['cityname.districtname.statename', 'cityname.statename'])
                    ->where('active', 'Y')
                    ->where('pincode', $data['pincode'])
                    ->first();
                $caller = null;
                if ($data['caller_email'] !== '') {
                    $caller = User::permission('call_management_access')
                        ->where('active', 'Y')
                        ->where('email', $data['caller_email'])
                        ->first();
                }

                if (! $pincode) {
                    throw ValidationException::withMessages(['import_file' => 'Row '.($index + 2).': Pincode not found in the system.']);
                }
                if ($data['caller_email'] !== '' && ! $caller) {
                    throw ValidationException::withMessages(['import_file' => 'Row '.($index + 2).': Caller email is not an active call-management user.']);
                }
                if (! $entry && ! $caller) {
                    throw ValidationException::withMessages(['import_file' => 'Row '.($index + 2).': Caller email is required for a new call entry.']);
                }

                $city = $pincode->cityname;
                $district = optional($city)->districtname;
                $state = optional($district)->statename ?: optional($city)->statename;

                $values = [
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
                    'assigned_user_id' => $caller ? $caller->id : $entry->assigned_user_id,
                    'custom_column_1' => $data['custom_column_1'] ?? null,
                    'custom_column_2' => $data['custom_column_2'] ?? null,
                    'custom_column_3' => $data['custom_column_3'] ?? null,
                    'custom_column_4' => $data['custom_column_4'] ?? null,
                    'status' => strtolower(trim((string) ($data['status'] ?? ''))) ?: ($entry ? $entry->status : 'pending'),
                ];

                if ($entry) {
                    $entry->update($values);
                    $this->updated++;
                } else {
                    CallManagementEntry::create(array_merge($values, [
                        'created_by' => $this->createdBy,
                    ]));
                    $this->created++;
                }
            } catch (ValidationException $exception) {
                $this->errors[] = collect($exception->errors())->flatten()->first()
                    ?: 'Row '.($index + 2).': Import failed.';
            }
        }
    }

    public function createdCount(): int
    {
        return $this->created;
    }

    public function updatedCount(): int
    {
        return $this->updated;
    }

    public function skippedCount(): int
    {
        return count($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
