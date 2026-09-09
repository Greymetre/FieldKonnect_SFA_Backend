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
        return ['Direction', 'Agent', 'Firm Name', 'Contact Person', 'Mobile', 'Date & Time', 'Duration', 'Call Status', 'Agent Status', 'Notes'];
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
            $isClient ? ucfirst($callLog->direction) : 'Outbound',
            optional($agent)->name,
            optional($entry)->firm_name,
            optional($entry)->contact_person_name,
            optional($entry)->mobile_number ?: ($isClient ? $callLog->customer_number : $callLog->number),
            optional($callLog->started_at)->format('d/m/Y h:i A'),
            sprintf('%02d:%02d:%02d', intdiv($duration, 3600), intdiv($duration % 3600, 60), $duration % 60),
            $callStatus,
            optional($callLog->feedbackStatus)->display_name ?: optional($callLog->feedbackStatus)->status_name,
            $callLog->remark,
        ];
    }
}
