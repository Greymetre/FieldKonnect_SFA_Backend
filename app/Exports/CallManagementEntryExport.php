<?php

namespace App\Exports;

use App\Models\CallManagementEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CallManagementEntryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return CallManagementEntry::with('assignedUser:id,name,email')->latest('id')->get();
    }

    public function headings(): array
    {
        return [
            'Project Name', 'Project ID', 'Parent Name', 'Firm Name',
            'Contact Person Name', 'Mobile Number', 'Customer Type',
            'Address', 'Pincode', 'City', 'District', 'State', 'Caller Email',
            'Caller Name', 'Point Column 1', 'Point Column 2', 'Point Column 3',
            'Point Column 4', 'Status',
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->project_name,
            $entry->project_id,
            $entry->parent_name,
            $entry->firm_name,
            $entry->contact_person_name,
            $entry->mobile_number,
            $entry->customer_type,
            $entry->address,
            $entry->pincode,
            $entry->city,
            $entry->district,
            $entry->state,
            optional($entry->assignedUser)->email,
            optional($entry->assignedUser)->name,
            $entry->custom_column_1,
            $entry->custom_column_2,
            $entry->custom_column_3,
            $entry->custom_column_4,
            $entry->status,
        ];
    }
}
