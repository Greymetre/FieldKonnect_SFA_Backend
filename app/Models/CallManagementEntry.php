<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallManagementEntry extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'created_by',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
