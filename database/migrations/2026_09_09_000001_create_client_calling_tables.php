<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_call_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('call_management_entry_id')->nullable()->index();
            $table->unsignedBigInteger('assigned_user_id')->nullable()->index();
            $table->unsignedBigInteger('feedback_status_id')->nullable()->index();
            $table->string('direction', 10)->index();
            $table->string('customer_number', 20)->index();
            $table->string('agent_number', 20)->nullable();
            $table->string('virtual_number', 20);
            $table->string('status', 50)->default('initiating')->index();
            $table->string('provider_call_uuid')->nullable()->unique();
            $table->string('provider_agent_leg_uuid')->nullable()->index();
            $table->string('provider_customer_leg_uuid')->nullable()->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ringing_at')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('duration')->default(0);
            $table->unsignedInteger('recording_duration')->nullable();
            $table->text('recording_url')->nullable();
            $table->string('recording_id')->nullable()->index();
            $table->decimal('cost', 12, 6)->nullable();
            $table->string('hangup_cause')->nullable();
            $table->text('remark')->nullable();
            $table->json('metadata')->nullable();
            $table->string('webhook_token', 64)->unique();
            $table->timestamps();
        });

        Schema::create('client_call_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_call_log_id')->nullable()->index();
            $table->string('provider_event_id')->nullable()->index();
            $table->string('event_type', 100)->index();
            $table->json('payload');
            $table->timestamp('received_at');
            $table->timestamp('processed_at')->nullable();
            $table->text('processing_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_call_events');
        Schema::dropIfExists('client_call_logs');
    }
};
