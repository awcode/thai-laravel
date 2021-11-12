<?php
namespace Awcode\ThaiLaravel;

use Awcode\ThaiLaravel\Helpers\ThaiAddress;
use Awcode\ThaiLaravel\Helpers\ThaiFormat;
use Awcode\ThaiLaravel\Helpers\ThaiPhone;

class ThaiLaravel
{
    
    static function makePhone($phone){
        return ThaiPhone::makePhone($phone);
    }
    
    static function validatePhone($phone){
        return ThaiPhone::makePhone($phone)->validate();
    }

    static function convert($from_format, $to_format, $value){
         return ThaiFormat::makeFormat($from_format, $value)->to($to_format);
    }
}
