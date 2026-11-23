<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'external_contact_id',
        'name',
        'title',
        'phone_number',
        'email',
        'url',
        'lead_source',
        'created_by',
    ];

    
    public function lead(){
        return $this->belongsTo(Lead::class);
    }

    /** Lead calls made to this contact. */
    public function callLogs(){
        return $this->hasMany(CallLog::class, 'lead_contact_id');
    }
}
