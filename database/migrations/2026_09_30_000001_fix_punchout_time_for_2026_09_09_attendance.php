<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Attendance of 09-Sep-2026 (punch in 10:08:00) was punched out at 18:30:00
    // by mistake; the correct punch out time is 19:30:00.
    private const PUNCHIN_DATE = '2026-09-09';
    private const PUNCHIN_TIME = '10:08:00';
    private const WRONG_PUNCHOUT_TIME = '18:30:00';
    private const CORRECT_PUNCHOUT_TIME = '19:30:00';

    public function up(): void
    {
        $this->updatePunchout(self::WRONG_PUNCHOUT_TIME, self::CORRECT_PUNCHOUT_TIME);
    }

    public function down(): void
    {
        $this->updatePunchout(self::CORRECT_PUNCHOUT_TIME, self::WRONG_PUNCHOUT_TIME);
    }

    private function updatePunchout(string $from, string $to): void
    {
        $attendances = DB::table('attendances')
            ->whereDate('punchin_date', self::PUNCHIN_DATE)
            ->where('punchin_time', self::PUNCHIN_TIME)
            ->where('punchout_time', $from)
            ->get(['id', 'punchin_date', 'punchin_time', 'punchout_date']);

        // Only touch the record when it is uniquely identified.
        if ($attendances->count() !== 1) {
            return;
        }

        $attendance = $attendances->first();
        $punchin = strtotime(date('Y-m-d', strtotime($attendance->punchin_date)) . ' ' . $attendance->punchin_time);
        $punchoutDate = $attendance->punchout_date ?: $attendance->punchin_date;
        $punchout = strtotime(date('Y-m-d', strtotime($punchoutDate)) . ' ' . $to);
        $seconds = max(0, $punchout - $punchin);

        DB::table('attendances')
            ->where('id', $attendance->id)
            ->update([
                'punchout_time' => $to,
                'worked_time' => sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60),
                'updated_at' => now(),
            ]);
    }
};
