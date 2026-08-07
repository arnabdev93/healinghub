<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrendingCategory extends Model
{
    public $appends = ['image_path'];
    public function getImagePathAttribute()
    {
        $image = $this->image;
        return $image ? asset('storage/').'/'.$image : null;
    }
}
