<?php

namespace App\Imports;

use App\Models\CallManagementEntry;
use App\Models\Pincode;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CallManagementEntryImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsEmptyRows
{
    private int $createdBy;
    private int $created = 0;
    private int $updated = 0;
    private array $errors = [];
    private ?int $nextListingOrder = null;

    public function __construct(int $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function collection(Collection $rows)
    {
        if ($this->nextListingOrder === null) {
            $this->nextListingOrder = max(
                (int) round(microtime(true) * 1000),
                ((int) CallManagementEntry::max('listing_order')) + 1000000
            );
        }

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) {
                continue;
            }

            try {
                $data = $row->toArray();
                $data['project_name'] = trim((string) ($data['project_name'] ?? '')) ?: null;
                $data['project_id'] = trim((string) ($data['project_id'] ?? '')) ?: null;
                $data['parent_name'] = trim((string) ($data['parent_name'] ?? '')) ?: null;
                $data['mobile_number'] = $this->digitsFromExcel($data['mobile_number'] ?? null);
                $data['pincode'] = $this->digitsFromExcel($data['pincode'] ?? null);
                $data['caller_email'] = trim((string) ($data['caller_email'] ?? ''));
                $data['caller_name'] = trim((string) ($data['caller_name'] ?? ''));
                foreach (['custom_column_1', 'custom_column_2', 'custom_column_3', 'custom_column_4'] as $customColumn) {
                    $data[$customColumn] = $this->textFromExcel($data[$customColumn] ?? null);
                }

                $validator = Validator::make($data, [
                    'project_name' => ['nullable', 'string', 'max:255'],
                    'project_id' => ['nullable', 'string', 'max:100'],
                    'parent_name' => ['nullable', 'string', 'max:255'],
                    'firm_name' => ['required', 'string', 'max:200'],
                    'contact_person_name' => ['required', 'string', 'max:200'],
                    'mobile_number' => ['required', 'digits:10'],
                    'customer_type' => ['nullable', 'string', 'max:100'],
                    'address' => ['nullable', 'string', 'max:500'],
                    'pincode' => ['required'],
                    'caller_email' => ['nullable', 'email'],
                    'caller_name' => ['nullable', 'string', 'max:255'],
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
                } elseif ($data['caller_name'] !== '') {
                    $caller = User::permission('call_management_access')
                        ->where('active', 'Y')
                        ->where('name', $data['caller_name'])
                        ->first();
                }

                if (! $pincode) {
                    throw ValidationException::withMessages(['import_file' => 'Row '.($index + 2).': Pincode not found in the system.']);
                }
                if ($data['caller_email'] !== '' && ! $caller) {
                    throw ValidationException::withMessages(['import_file' => 'Row '.($index + 2).': Caller email is not an active call-management user.']);
                }
                if ($data['caller_email'] === '' && $data['caller_name'] !== '' && ! $caller) {
                    throw ValidationException::withMessages(['import_file' => 'Row '.($index + 2).': Caller name is not an active call-management user.']);
                }
                if (! $entry && ! $caller) {
                    throw ValidationException::withMessages(['import_file' => 'Row '.($index + 2).': Caller email or caller name is required for a new call entry.']);
                }

                $city = $pincode->cityname;
                $district = optional($city)->districtname;
                $state = optional($district)->statename ?: optional($city)->statename;

                $values = [
                    'project_name' => $data['project_name'] ?? null,
                    'project_id' => $data['project_id'] ?? null,
                    'parent_name' => $data['parent_name'] ?? null,
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
                    'status' => $this->importedEntryStatus($data['status'] ?? null),
                    'listing_order' => $this->nextListingOrder--,
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

    private function importedEntryStatus($status): string
    {
        $normalized = preg_replace('/[^a-z0-9]+/', '', strtolower(trim((string) $status)));

        return in_array($normalized, ['complete', 'completed', 'callcomplete', 'callcompleted', 'done'], true)
            ? 'completed'
            : 'assigned';
    }

    private function digitsFromExcel($value): string
    {
        if (is_numeric($value)) {
            $value = number_format((float) $value, 0, '.', '');
        }

        return preg_replace('/\D+/', '', (string) $value);
    }

    private function textFromExcel($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_scalar($value)) {
            return trim((string) $value) ?: null;
        }

        return null;
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
