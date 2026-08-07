<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'customer_id',
        'notes',
        'image'
    ];

    public function appointment()
    {
        return $this->belongsTo(BookAppointment::class, 'appointment_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
