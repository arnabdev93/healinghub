<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BookingSlot extends Model
{
    protected $fillable = [
        'user_id',
        'weekday',
        'times',
        'slot_duration',
    ];

    protected $casts = [
        'times' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
