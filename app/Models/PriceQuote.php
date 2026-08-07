<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceQuote extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'notes',
        'images',
        'price',
        'status'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
