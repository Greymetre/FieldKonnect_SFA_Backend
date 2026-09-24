<?php

namespace App\Exports;

use App\Models\CallManagementEntry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerCallHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Collection $callLogs, private string $historyType = CallManagementEntry::TYPE_CUSTOMER_CALLING)
    {
    }

    public function collection(): Collection
    {
        return $this->callLogs;
    }

    public function headings(): array
    {
        return [
            'Call ID', 'Project Name', 'Campaign ID', 'Parent Name', 'Firm Name',
            'Contact Person Name', 'Mobile Number', 'Customer Type',
            'Address', 'Pincode', 'City', 'District', 'State',
            'Point Column 1', 'Point Column 2', 'Point Column 3',
            'Point Column 4', 'Status', 'Direction', 'Agent', 'Date & Time',
            'Duration', 'Call Status', 'Agent Status', 'Notes',
        ];
    }

    public function map($callLog): array
    {
        $isClient = $this->historyType === CallManagementEntry::TYPE_CLIENT_CALLING;
        $duration = (int) $callLog->duration;
        $callStatus = $isClient
            ? ($callLog->status ?: 'Initiated')
            : ($duration > 0 || $callLog->recording_url || (int) $callLog->status === 1
                ? 'Completed'
                : ($callLog->plivo_status ?: 'Initiated'));
        $entry = $isClient ? $callLog->entry : $callLog->callManagementEntry;
        $agent = $isClient ? $callLog->assignedAgent : $callLog->user;

        return [
            $callLog->public_id ?? $callLog->id,
            optional($entry)->project_name,
            optional($entry)->project_id,
            optional($entry)->parent_name,
            optional($entry)->firm_name,
            optional($entry)->contact_person_name,
            optional($entry)->mobile_number ?: ($isClient ? $callLog->customer_number : $callLog->number),
            optional($entry)->customer_type,
            optional($entry)->address,
            optional($entry)->pincode,
            optional($entry)->city,
            optional($entry)->district,
            optional($entry)->state,
            optional($entry)->custom_column_1,
            optional($entry)->custom_column_2,
            optional($entry)->custom_column_3,
            optional($entry)->custom_column_4,
            optional($entry)->status,
            $isClient ? ucfirst($callLog->direction) : 'Outbound',
            optional($agent)->name,
            optional($callLog->started_at)->format('d/m/Y h:i A'),
            sprintf('%02d:%02d:%02d', intdiv($duration, 3600), intdiv($duration % 3600, 60), $duration % 60),
            $callStatus,
            optional($callLog->feedbackStatus)->display_name ?: optional($callLog->feedbackStatus)->status_name,
            $callLog->remark,
        ];
    }
}
