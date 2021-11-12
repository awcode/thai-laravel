<?php
namespace Awcode\ThaiLaravel\Helpers;


class ThaiFormat
{
    protected $orig_value;
    protected $orig_format;
    
    protected $new_value;
    protected $new_format;
    
    public function __toString()
    {
        try 
        {
            return (string) $this->new_value;
        } 
        catch (Exception $exception) 
        {
            return '';
        }
    }
    
    static function makeFormat($from_format, $value){
        $format = new ThaiFormat;
        $format->orig_format = $from_format;
        $format->orig_value = $value;

        return $format;
    }

    
    public function to($to_format){
        if(!is_numeric($this->orig_value)){
            return $this->handleError('Non numeric value '.$this->orig_value);
        }
        $rate = $this->getRate($this->orig_format, $to_format);
        if($rate === false){return null;}

        if(in_array($to_format, $this->precision_large)){
            $precision = config('thai-laravel.format_precision_large');
        }else{
            $precision = config('thai-laravel.format_precision_small');
        }
        
        $this->new_format = $to_format;
        $this->new_value = round(($rate * $this->orig_value), $precision);
        
        return $this->new_value;
    }
    public function getRate($orig_format, $to_format){
        if(isset($this->conversions[$orig_format.'_'.$to_format])){
            return $this->conversions[$orig_format.'_'.$to_format];
        }
        return $this->handleError('Conversion pair not found: '.$orig_format. ' -> '.$to_format);
    }
    
    protected function handleError($message){
        if(config('thai-laravel.format_errors') == 'strict'){
            \Log::error($message);
        }elseif(env('format_errors') == 'log'){
            \Log::info($message);
        }
        return false;
    }
    
    protected $conversions = [
        'rai_sqm' => 1600,  'sqm_rai' => (1/1600),
        'wah_sqm' => 4,     'sqm_wah' => (1/4),
    ];
    
    protected $precision_large = [//large measures, have seperate precision setting to smaller ones
        'rai'
    ];
}
