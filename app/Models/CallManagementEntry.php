<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallManagementEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'project_id',
        'parent_name',
        'firm_name',
        'contact_person_name',
        'mobile_number',
        'customer_type',
        'address',
        'pincode_id',
        'pincode',
        'city',
        'district',
        'state',
        'assigned_user_id',
        'custom_column_1',
        'custom_column_2',
        'custom_column_3',
        'custom_column_4',
        'status',
        'follow_up_date',
        'listing_order',
        'created_by',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (CallManagementEntry $entry) {
            if ($entry->listing_order === null) {
                $entry->listing_order = ((int) static::max('listing_order')) + 1;
            }
        });
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function latestCallLog()
    {
        return $this->hasOne(CallLog::class, 'call_management_entry_id')->latestOfMany();
    }

    public function latestNotedCallLog()
    {
        return $this->hasOne(CallLog::class, 'call_management_entry_id')
            ->whereNotNull('remark')
            ->where('remark', '!=', '')
            ->latestOfMany();
    }
}
