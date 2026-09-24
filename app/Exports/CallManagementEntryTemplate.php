<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CallManagementEntryTemplate implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection(): Collection
    {
        return collect();
    }

    // Same columns CallManagementEntryImport reads, in the export's order,
    // without the report-only "Agent Call Status" column.
    public function headings(): array
    {
        return [
            'Project Name', 'Caller ID', 'Parent Name', 'Firm Name',
            'Contact Person Name', 'Mobile Number', 'Customer Type',
            'Address', 'Pincode', 'City', 'District', 'State', 'Caller Email',
            'Caller Name', 'Point Column 1', 'Point Column 2', 'Point Column 3',
            'Point Column 4', 'Status', 'Customer Calling', 'Client Calling',
        ];
    }
}
