<?php

namespace Awcode\ThaiLaravel;

use Illuminate\Support\Facades\Facade;

class ThaiLaravelFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'thai-laravel';
    }

}
