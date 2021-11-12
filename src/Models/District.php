<?php

namespace Awcode\ThaiLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $table = 'districts';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'province_id',
        'district_name_th',
        'district_name_eng',
        'sort_order',
        'postcode',
        'latlng',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function subDistricts()
    {
        return $this->hasMany(SubDistrict::class, 'district_id', 'id');
    }


    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
    
    static function findByPostcode($postcode){
        return District::where('postcode', $postcode)->get();
    }
    
    public function getName(){
        if(config('thai-laravel.default_language') == 'th'){
            return $this->getNameThai();
        }
        return $this->getNameEnglish();
    }
    public function getNameThai(){
        return $this->district_name_th ? $this->district_name_th : $this->district_name_eng;
    }
    public function getNameEnglish(){
        return $this->district_name_eng ? $this->district_name_eng : $this->district_name_th;
    }
}
