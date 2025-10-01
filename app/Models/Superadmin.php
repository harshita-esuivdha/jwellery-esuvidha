<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // allows login
use Illuminate\Notifications\Notifiable;

class Superadmin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'superadmins';

    // ✅ Add new fields to fillable
    protected $fillable = [
        'company_name',
        'address',
        'mobile',
        'company_limit',
        'email',
        'password',
        'expiry_date',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token', // optional if you want "remember me" functionality
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];
}
