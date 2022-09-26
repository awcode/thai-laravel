<?php
namespace Awcode\ThaiLaravel\Helpers;


class ThaiNumerals
{
    static function thaiArray(){
        return $thai = [ '๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'];
    }
    static function arabicArray(){
        return $arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    }
    
    static function toThai($value){
        
        return str_replace(ThaiNumerals::arabicArray() , ThaiNumerals::thaiArray() , $value);

    }

    static function toArabic($value){
        
        return str_replace(ThaiNumerals::thaiArray(), ThaiNumerals::arabicArray(), $value);

    }
    

}
