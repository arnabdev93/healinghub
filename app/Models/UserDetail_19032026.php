<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    public $appends = ['image_path'];
    public function getImagePathAttribute()
    {
        $image = $this->image;
        return $image ? asset('storage/').'/'.$image : null;
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
