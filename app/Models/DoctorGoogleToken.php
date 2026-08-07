<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorGoogleToken extends Model
{
    protected $fillable = [
        'doctor_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
    ];
}
