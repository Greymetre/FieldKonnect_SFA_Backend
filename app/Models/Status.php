<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public const MODULE_LEAD_CALL_FEEDBACK = 'CallFeedbackStatus';
    public const MODULE_CALL_MANAGEMENT_FEEDBACK = 'CallManagementFeedback';

    // Lead call outcomes, in the order they are shown after a call.
    public const LEAD_CALL_OUTCOMES = ['lead_call_done', 'lead_call_no_response', 'lead_call_switch_off', 'lead_call_wrong_number', 'lead_call_back'];
    public const LEAD_CALL_BACK = 'lead_call_back';

    protected $table = 'statuses';

    protected $fillable = [ 'active', 'status_name', 'display_name', 'status_message', 'module', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    public function createdbyname()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id')->select('id','name');
    }
}
