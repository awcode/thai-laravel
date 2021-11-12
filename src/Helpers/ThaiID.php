<?php
namespace Awcode\ThaiLaravel\Helpers;


class ThaiID
{
    protected $raw_value;
    protected $value;
    
    protected $is_national;
    protected $is_migrant;
    protected $is_foreigner;
    
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
    
    static function makeCard($id_number){
        $id = new ThaiPhone;
        $id->raw_value = $id_number;
        
        $id = preg_replace('/[^0-9]/i', '', $id_number);
        $id->value = $id_number;

        return $id;
    }

    
    public function validate(){
        $id = $this->value;
        if(!$id){return false;}
        $length = strlen($id);
        
        if($length != 12){
            return false;
        }
        //TODO - validate checksum digit
        
        //TODO - setup all prefixes
        if($phone[0] == '6'){
            $this->is_foreigner = true;
        }
        return $this;
    }
    
    public function isThaiNational(){
        return $this->is_national ? true : false;
    }
    public function isMigrant(){
        return $this->is_migrant ? true : false;
    }
    public function isForeigner(){
        return $this->is_foreigner ? true : false;
    }
}
