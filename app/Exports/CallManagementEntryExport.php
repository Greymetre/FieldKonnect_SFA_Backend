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
        return CallManagementEntry::with([
            'assignedUser:id,name,email',
            'latestCallLog.feedbackStatus:id,status_name,display_name',
        ])->withCount(['callLogs as feedback_call_count' => function ($callLogQuery) {
            $callLogQuery->whereNotNull('feedback_status_id');
        }])->latest('id')->get();
    }

    public function headings(): array
    {
        return [
            'Project Name', 'Caller ID', 'Parent Name', 'Firm Name',
            'Contact Person Name', 'Mobile Number', 'Customer Type',
            'Address', 'Pincode', 'City', 'District', 'State', 'Caller Email',
            'Caller Name', 'Point Column 1', 'Point Column 2', 'Point Column 3',
            'Point Column 4', 'Status', 'Customer Calling', 'Client Calling',
            // Report-only column: the import does not read it.
            'Agent Call Status',
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->project_name,
            $entry->caller_id,
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
            $this->status($entry),
            $entry->calling_type === CallManagementEntry::TYPE_CLIENT_CALLING ? 'No' : 'Yes',
            $entry->calling_type === CallManagementEntry::TYPE_CLIENT_CALLING ? 'Yes' : 'No',
            $entry->feedback_call_count > 0 ? 'Called' : 'Not Called',
        ];
    }

    // Match the Customer Calling listing: open entries show their latest call
    // feedback (e.g. Follow Up). Completed entries keep "completed" so a
    // re-import of this file does not reopen them.
    private function status(CallManagementEntry $entry): ?string
    {
        if ($entry->status !== 'assigned') {
            return $entry->status;
        }

        $feedbackStatus = optional($entry->latestCallLog)->feedbackStatus;

        return optional($feedbackStatus)->display_name ?: optional($feedbackStatus)->status_name ?: $entry->status;
    }
}
