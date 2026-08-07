<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $appends = ['image_path'];
    public function getImagePathAttribute()
    {
        $image = $this->image;
        return $image ? asset('storage/').'/'.$image : null;
    }
    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }
}
