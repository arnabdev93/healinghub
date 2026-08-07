<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'appointment_id',
        'user_id',
        'doctor_id',
        'payment_method_id',
        'transaction_id',
        'amount',
        'status',
        'paid_at'
    ];

    public function appointment()
    {
        return $this->belongsTo(BookAppointment::class,'appointment_id');
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_method_id');
    }
}
