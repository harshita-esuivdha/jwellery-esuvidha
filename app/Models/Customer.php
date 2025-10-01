<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'cid',
        'name',
        'customer_group',
        'address',
        'city',
        'area',
        'district',
        'state',
        'pin_code',
        'pan_number',
        'aadhaar_number',
        'phone',
        'email',
        'dob',
        'bank_name',
        'bank_account',
        'ifsc_code',
    ];
}
