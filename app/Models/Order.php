<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'orderno',
        'user_id',
        'address_id',
        'appointment_id',
        'type',
        'status',
        'notes'
    ];

    protected $appends = ['item_images_array'];
    
    public function getItemImagesArrayAttribute()
    {
        return !empty($this->item_images) ? explode(',', $this->item_images) : [];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class,'order_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function appointment()
    {
        return $this->belongsTo(BookAppointment::class,'appointment_id');
    }
}
