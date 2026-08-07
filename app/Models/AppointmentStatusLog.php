<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BookAppointment;
use App\Models\User;

class AppointmentStatusLog extends Model
{
    protected $fillable = [
        'appointment_id',
        'changed_by',
        'note',
        'new_status',
        'changed_at'
    ];

    public function appointment()
    {
        return $this->belongsTo(BookAppointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
