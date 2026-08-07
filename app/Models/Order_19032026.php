<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $appends = ['item_images_array'];
    
    public function getItemImagesArrayAttribute()
    {
        return !empty($this->item_images) ? explode(',', $this->item_images) : [];
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class,'order_id');
    }
}
