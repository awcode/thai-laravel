<?php

namespace Awcode\ThaiLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $table = 'provinces';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name_th',
        'name_eng',
        'sort_order',
        'postcode_prefix',
        'latlng',
        'region_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
    
    static function findByPostcode($postcode){
        return Province::where('postcode_prefix', substr($postcode, 0, 2) )->first();
    }
    
    public function getName(){
        if(config('thai-laravel.default_language') == 'th'){
            return $this->getNameThai();
        }
        return $this->getNameEnglish();
    }
    public function getNameThai(){
        return $this->name_th ? $this->name_th : $this->name_eng;
    }
    public function getNameEnglish(){
        return $this->name_eng ? $this->name_eng : $this->name_th;
    }
}
