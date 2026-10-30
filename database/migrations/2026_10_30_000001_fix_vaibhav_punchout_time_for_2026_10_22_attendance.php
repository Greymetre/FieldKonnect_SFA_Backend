<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Vaibhav Vishvkarma's attendance of 22-Oct-2026 (punch in 09:46:00) was
    // punched out at 10:34:00 by mistake; the correct punch out time is 20:34:00.
    private const USER_NAME_LIKE = '%vaibhav%';
    private const PUNCHIN_DATE = '2026-10-22';
    private const PUNCHIN_TIME = '09:46:00';
    private const WRONG_PUNCHOUT_TIME = '10:34:00';
    private const CORRECT_PUNCHOUT_TIME = '20:34:00';

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
            ->join('users', 'users.id', '=', 'attendances.user_id')
            ->where('users.name', 'like', self::USER_NAME_LIKE)
            ->whereDate('attendances.punchin_date', self::PUNCHIN_DATE)
            ->where('attendances.punchin_time', self::PUNCHIN_TIME)
            ->where('attendances.punchout_time', $from)
            ->get(['attendances.id', 'attendances.punchin_date', 'attendances.punchin_time', 'attendances.punchout_date']);

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
