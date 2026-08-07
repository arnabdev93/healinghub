<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'name',
        'image'
    ];

    public $appends = ['image_path'];

    public function getImagePathAttribute()
    {
        $image = $this->image;
        return $image ? asset('storage/').'/'.$image : null;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
