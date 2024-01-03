<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class JwToken extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'jwtoken';
    }
}