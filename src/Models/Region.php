<?php

namespace Awcode\ThaiLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $table = 'regions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'region_name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function provinces()
    {
        return $this->hasMany(Province::class, 'region_id', 'id');
    }
    
    public function getName(){
        return $this->region_name ;
    }
}
