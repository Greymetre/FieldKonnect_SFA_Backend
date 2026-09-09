<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCallEvent extends Model
{
    protected $fillable = [
        'client_call_log_id', 'provider_event_id', 'event_type', 'payload',
        'received_at', 'processed_at', 'processing_error',
    ];

    protected $casts = [
        'payload' => 'array', 'received_at' => 'datetime', 'processed_at' => 'datetime',
    ];
}
