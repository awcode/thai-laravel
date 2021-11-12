<?php
namespace Awcode\ThaiLaravel\Helpers;


class ThaiPhone
{
    protected $raw_value;
    protected $value;
    
    protected $is_mobile;
    protected $is_landline;
    protected $is_shortcode;
    
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
    
    static function makePhone($phone_number){
        $phone = new ThaiPhone;
        $phone->raw_value = $phone_number;
        
        $phone_number = preg_replace('/[^0-9+]/i', '', $phone_number);
        $phone_number = str_replace('+660', '+66', $phone_number);
        $phone_number = str_replace('+66', '0', $phone_number);
        $phone->value = $phone_number;

        return $phone;
    }

    
    public function validate(){
        $phone = $this->value;//Already filtered to numeric without country code
        if(!$phone){return false;}
        $length = strlen($phone);
        
        if($length== 4){//4 digit shortcodes can be valid
            $this->is_shortcode = true;
        }elseif($length < 9 && $length > 10){//TODO - check these ranges
            return false;//Too long or short
        }elseif($phone[0] != 0){
            return false;//Unless shortcode must start with zero
        }
        
        if($phone[1] == '2' || $phone[1] == '3'){
            $this->is_landline = true;
        }
        if($phone[1] == '8'){
            $this->is_mobile = true;
        }
        return $this;
    }
    
    
    public function isMobile(){
        return $this->is_mobile ? true : false;
    }
    public function isLandline(){
        return $this->is_landline ? true : false;
    }
    public function isShortcode(){
        return $this->is_shortcode ? true : false;
    }
    
    public function toInternational(){
        $phone = $this->value;//Already filtered to numeric without country code
        if(!$phone){return '';}
        if($phone[0] == 0){
            return '+66'.substr($phone, 1);
        }
    }
}
