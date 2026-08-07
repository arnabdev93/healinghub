<?php

namespace App\Helpers;

class CustomHelper
{
	public static function removeExistingFileFromStorage($path='')
	{
		if($path){
			if (\Storage::disk('public')->exists($path)) {
                \Storage::disk('public')->delete($path);
            }
		}
		return 1;
	}
	public static function moneyRound($amount)
	{
		return round($amount, 2);
	}
}