<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Email / LinkedIn drafts:
 *  - lead_contact_id: the contact the draft is written for.
 *  - changes_requested_at: set when the approver sends feedback; cleared when
 *    the agent edits the draft (it goes back for approval) or it is approved.
 *  - lead_note_feedback: every feedback the approver gave, oldest first.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_notes', function (Blueprint $table) {
            if (! Schema::hasColumn('lead_notes', 'lead_contact_id')) {
                $table->unsignedBigInteger('lead_contact_id')->nullable()->index()->after('lead_id');
            }
            if (! Schema::hasColumn('lead_notes', 'changes_requested_at')) {
                $table->timestamp('changes_requested_at')->nullable()->after('approved_at');
            }
        });

        if (! Schema::hasTable('lead_note_feedback')) {
            Schema::create('lead_note_feedback', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_note_id')->index();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->text('feedback');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_note_feedback');
        Schema::table('lead_notes', function (Blueprint $table) {
            if (Schema::hasColumn('lead_notes', 'changes_requested_at')) {
                $table->dropColumn('changes_requested_at');
            }
            if (Schema::hasColumn('lead_notes', 'lead_contact_id')) {
                $table->dropIndex(['lead_contact_id']);
                $table->dropColumn('lead_contact_id');
            }
        });
    }
};
