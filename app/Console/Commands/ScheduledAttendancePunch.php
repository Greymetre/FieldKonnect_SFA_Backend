<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScheduledAttendancePunch extends Command
{
    protected $signature = 'attendance:scheduled-punch {action : in or out}';

    protected $description = 'Create scheduled attendance punch-in or punch-out records';

    private const USER_IDS = [19, 29];
    private const LATITUDE = '22.771557';
    private const LONGITUDE = '75.897860';

    public function handle(): int
    {
        $action = strtolower($this->argument('action'));

        if (!in_array($action, ['in', 'out'], true)) {
            $this->error('The action must be either "in" or "out".');

            return self::INVALID;
        }

        $now = Carbon::now('Asia/Kolkata');

        foreach (self::USER_IDS as $userId) {
            if ($action === 'in') {
                $this->punchIn($userId, $now);
            } else {
                $this->punchOut($userId, $now);
            }
        }

        return self::SUCCESS;
    }

    private function punchIn(int $userId, Carbon $now): void
    {
        $punchInTime = $this->randomTime(9, 45, 10, 15);

        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $userId,
                'punchin_date' => $now->toDateString(),
            ],
            [
                'active' => 'Y',
                'flag' => 'true',
                'punchin_time' => $punchInTime,
                'punchin_latitude' => self::LATITUDE,
                'punchin_longitude' => self::LONGITUDE,
                'punchin_address' => '',
                'punchin_image' => '',
                'punchin_summary' => '',
                'working_type' => 'office work',
                'city' => 'Indore',
                'punchin_from' => 'Cron',
                'created_at' => $now,
            ]
        );

        $this->line(sprintf(
            'User %d punch-in %s (%s)',
            $userId,
            $attendance->wasRecentlyCreated ? 'created' : 'already exists',
            $attendance->punchin_time
        ));
    }

    private function punchOut(int $userId, Carbon $now): void
    {
        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('punchin_date', $now->toDateString())
            ->whereNull('punchout_date')
            ->first();

        if (!$attendance) {
            $this->line("User {$userId} has no open attendance for {$now->toDateString()}.");

            return;
        }

        $punchOutTime = $this->randomTime(19, 30, 20, 15);
        $punchOutAt = Carbon::parse($now->toDateString() . ' ' . $punchOutTime, 'Asia/Kolkata');
        $punchInAt = Carbon::parse($attendance->punchin_date . ' ' . $attendance->punchin_time, 'Asia/Kolkata');

        $attendance->update([
            'punchout_date' => $now->toDateString(),
            'punchout_time' => $punchOutTime,
            'punchout_latitude' => self::LATITUDE,
            'punchout_longitude' => self::LONGITUDE,
            'punchout_address' => '',
            'punchout_image' => '',
            'punchout_summary' => 'all task completed',
            'worked_time' => gmdate('H:i:s', max(0, $punchInAt->diffInSeconds($punchOutAt))),
        ]);

        $this->line("User {$userId} punch-out created ({$punchOutTime}).");
    }

    private function randomTime(int $startHour, int $startMinute, int $endHour, int $endMinute): string
    {
        $start = ($startHour * 60) + $startMinute;
        $end = ($endHour * 60) + $endMinute;
        $minute = random_int($start, $end);

        return sprintf('%02d:%02d:00', intdiv($minute, 60), $minute % 60);
    }
}