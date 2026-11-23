<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Older lead calls were saved without lead_contact_id (only the number).
 * Link them to the lead contact with the same phone number, so "which
 * contacts have been called" can be worked out from lead_contact_id alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        $last10 = fn ($number) => substr(preg_replace('/\D+/', '', (string) $number), -10);

        DB::table('call_logs')
            ->whereNotNull('lead_id')
            ->whereNull('lead_contact_id')
            ->orderBy('id')
            ->select('id', 'lead_id', 'number')
            ->chunkById(500, function ($calls) use ($last10) {
                $contactsByLead = DB::table('lead_contacts')
                    ->whereIn('lead_id', $calls->pluck('lead_id')->unique())
                    ->get(['id', 'lead_id', 'phone_number'])
                    ->groupBy('lead_id');

                foreach ($calls as $call) {
                    $target = $last10($call->number);
                    if (strlen($target) !== 10) {
                        continue;
                    }
                    $contact = ($contactsByLead[$call->lead_id] ?? collect())
                        ->first(fn ($contact) => $last10($contact->phone_number) === $target);
                    if ($contact) {
                        DB::table('call_logs')->where('id', $call->id)->update(['lead_contact_id' => $contact->id]);
                    }
                }
            });
    }

    public function down(): void
    {
        // Linking is data-only; nothing to undo safely.
    }
};
