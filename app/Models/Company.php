<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ Important
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'companies';

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

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
