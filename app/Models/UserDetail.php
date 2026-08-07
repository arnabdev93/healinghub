<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserDetail extends Model
{
    public $appends = ['image_path'];

    public function getImagePathAttribute()
    {
        $image = $this->image;
        if(!$this->hasFullStoragePath($image)){
            $imageUrl = asset('storage/').'/'.$image;
        }else{
            $imageUrl = $image;
        }
        return $image ? $imageUrl : null;
    }

    public function hasFullStoragePath($imagePath)
    {
        if (empty($imagePath)) {
            return false;
        }

        $target = "https://dev8.codebuzzers.net/healinghub//public/storage/";

        return str_contains($imagePath, $target);
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
