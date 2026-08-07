<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    public $appends = ['image_path'];
    public function getImagePathAttribute()
    {
        $image = $this->image;
        return $image ? asset('storage/').'/'.$image : null;
    }
    // public function getImagePathAttribute()
    // {
    //     $image = $this->image;
    //     if(!$this->hasFullStoragePath($image)){
    //         $imageUrl = asset('storage/').'/'.$image;
    //     }else{
    //         $imageUrl = $image;
    //     }
    //     return $image ? $imageUrl : null;
    // }

    // public function hasFullStoragePath($imagePath)
    // {
    //     if (empty($imagePath)) {
    //         return false;
    //     }

    //     $target = "https://dev8.codebuzzers.net/healinghub//public/storage/";

    //     return str_contains($imagePath, $target);
    // }
}
