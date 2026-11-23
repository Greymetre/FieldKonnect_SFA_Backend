<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Feedback an approver gave on an Email / LinkedIn draft (asking for changes). */
class LeadNoteFeedback extends Model
{
    protected $table = 'lead_note_feedback';

    protected $fillable = ['lead_note_id', 'user_id', 'feedback'];

    public function draft()
    {
        return $this->belongsTo(LeadNote::class, 'lead_note_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
