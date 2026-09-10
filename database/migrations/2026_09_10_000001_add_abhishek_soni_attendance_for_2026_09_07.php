<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const USER_ID = 29;
    private const PUNCHIN_DATE = '2026-09-07';
    private const PUNCHIN_TIME = '10:12:00';
    private const PUNCHIN_FROM = 'Data Migration';

    public function up(): void
    {
        if (!DB::table('users')->where('id', self::USER_ID)->exists()) {
            throw new \RuntimeException('User ID 29 was not found; attendance was not inserted.');
        }

        $attendanceExists = DB::table('attendances')
            ->where('user_id', self::USER_ID)
            ->where('punchin_date', self::PUNCHIN_DATE)
            ->exists();

        if ($attendanceExists) {
            return;
        }

        DB::table('attendances')->insert([
            'active' => 'Y',
            'user_id' => self::USER_ID,
            'punchin_date' => self::PUNCHIN_DATE,
            'punchin_time' => self::PUNCHIN_TIME,
            'punchin_longitude' => '75.897860',
            'punchin_latitude' => '22.771557',
            'punchin_address' => 'Kanchan Vihar Public Park, Indore, MP, India',
            'punchin_image' => '',
            'punchout_address' => '',
            'punchout_image' => '',
            'punchin_summary' => '',
            'punchout_summary' => '',
            'flag' => 'true',
            'worked_time' => '',
            'attendance_status' => 0,
            'punchin_from' => self::PUNCHIN_FROM,
            'working_type' => 'office work',
            'city' => 'Indore',
            'created_at' => self::PUNCHIN_DATE . ' ' . self::PUNCHIN_TIME,
            'updated_at' => self::PUNCHIN_DATE . ' ' . self::PUNCHIN_TIME,
        ]);
    }

    public function down(): void
    {
        DB::table('attendances')
            ->where('user_id', self::USER_ID)
            ->where('punchin_date', self::PUNCHIN_DATE)
            ->where('punchin_time', self::PUNCHIN_TIME)
            ->where('punchin_from', self::PUNCHIN_FROM)
            ->delete();
    }
};
