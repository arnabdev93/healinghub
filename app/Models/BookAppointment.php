<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookAppointment extends Model
{
    protected $fillable = [
        'appointment_no',
        'user_id',
        'doctor_id',
        'booking_date',
        'weekday',
        'booking_time',
        'appointment_type',
        'amount',
        'razorpay_order_id',
        'notes',
        'status',
        'meeting_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'appointment_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(AppointmentStatusLog::class,'appointment_id');
    }

    public static function generateAppointmentNo()
    {
        $date = now()->format('Ymd');

        $lastAppointment = self::whereDate('created_at', now()->toDateString())
            ->latest('id')
            ->first();

        if ($lastAppointment) {
            $lastNumber = intval(substr($lastAppointment->appointment_no, -4));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'APT-' . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class,'appointment_id');
    }
    public function address()
    {
        return $this->hasOne(Address::class, 'user_id');
    }
}
