<?php
namespace Awcode\ThaiLaravel\Helpers;


class ThaiNumerals
{
    
    protected $value;
    protected $arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    protected $thai = [ '๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'];
    
    public function __toString()
    {
        try 
        {
            return (string) $this->value;
        } 
        catch (Exception $exception) 
        {
            return '';
        }
    }
    
    static function toThai($value){
        
        $this->value = str_replace($this->arabic, $this->thai, $value);

        return $this->value;
    }

    static function toArabic($value){
        
        $this->value = str_replace($this->thai, $this->arabic, $value);

        return $this->value;
    }
    
}
