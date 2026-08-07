<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $appends = ['image_path','medicine_power_array'];
    public function getImagePathAttribute()
    {
        $image = $this->image;
        return $image ? asset('storage/').'/'.$image : null;
    }
    public function getMedicinePowerArrayAttribute()
    {
        $medicine_power = $this->medicine_power;
        return !empty($medicine_power) ? explode(',', $medicine_power) : [];
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class,'product_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function prices()
    {
        return $this->hasMany(ProductPrice::class,'product_id');
    }
    public function trending_categories()
    {
        return $this->belongsToMany(Category::class, 'trending_category_products', 'product_id', 'category_id')->withPivot('category_id');
    }
}
