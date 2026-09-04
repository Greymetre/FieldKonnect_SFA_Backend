<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('call_logs', function (Blueprint $table) {
            $table->string('transcription_status', 40)->nullable()->index()->after('recording_id');
            $table->longText('transcript')->nullable()->after('transcription_status');
            $table->json('diarized_transcript')->nullable()->after('transcript');
            $table->string('sarvam_job_id')->nullable()->index()->after('diarized_transcript');
            $table->text('transcription_error')->nullable()->after('sarvam_job_id');
        });
    }

    public function down(): void
    {
        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropIndex(['transcription_status']);
            $table->dropIndex(['sarvam_job_id']);
            $table->dropColumn(['transcription_status', 'transcript', 'diarized_transcript', 'sarvam_job_id', 'transcription_error']);
        });
    }
};
