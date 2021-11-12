<?php

namespace Awcode\ThaiLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    public $table = 'sub_districts';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'district_id',
        'subdistrict_name_th',
        'subdistrict_name_eng',
        'sort_order',
        'latlng',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    
    public function getName(){
        if(config('thai-laravel.default_language') == 'th'){
            return $this->getNameThai();
        }
        return $this->getNameEnglish();
    }
    public function getNameThai(){
        return $this->subdistrict_name_th ? $this->subdistrict_name_th : $this->subdistrict_name_eng;
    }
    public function getNameEnglish(){
        return $this->subdistrict_name_eng ? $this->subdistrict_name_eng : $this->subdistrict_name_th;
    }
}
