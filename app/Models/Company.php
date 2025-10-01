<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // Table name (optional if it matches plural of model)
    protected $table = 'companies';

    // Fields you allow for mass assignment
    protected $fillable = [
        'name',
        'address',
        'bill_no',
        'itstate',
        'city',
        'pincode',
        'district',
        'mobile',
        'email',
        'website',
        'gst_no',
        'pan_no',
        'fy_start',
        'fy_end',
        'bank_name',
        'account_no',
        'ifsc',
        'username',
        'password',
        'parent_id',
        'logo',
    ];

    // Hide sensitive fields when converting to array/json
    protected $hidden = [
        'password',
    ];
}
