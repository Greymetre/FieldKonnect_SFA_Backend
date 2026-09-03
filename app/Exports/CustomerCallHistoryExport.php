<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerCallHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Collection $callLogs)
    {
    }

    public function collection(): Collection
    {
        return $this->callLogs;
    }

    public function headings(): array
    {
        return ['Agent', 'Firm Name', 'Contact Person', 'Mobile', 'Date & Time', 'Duration', 'Call Status', 'Agent Status', 'Notes'];
    }

    public function map($callLog): array
    {
        $duration = (int) $callLog->duration;
        $callStatus = $duration > 0 || $callLog->recording_url || (int) $callLog->status === 1
            ? 'Completed'
            : ($callLog->plivo_status ?: 'Initiated');

        return [
            optional($callLog->user)->name,
            optional($callLog->callManagementEntry)->firm_name,
            optional($callLog->callManagementEntry)->contact_person_name,
            optional($callLog->callManagementEntry)->mobile_number ?: $callLog->number,
            optional($callLog->started_at)->format('d/m/Y h:i A'),
            sprintf('%02d:%02d:%02d', intdiv($duration, 3600), intdiv($duration % 3600, 60), $duration % 60),
            $callStatus,
            optional($callLog->feedbackStatus)->display_name ?: optional($callLog->feedbackStatus)->status_name,
            $callLog->remark,
        ];
    }
}
