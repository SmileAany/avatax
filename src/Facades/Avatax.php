<?php

namespace Smbear\Avatax\Facades;

use Illuminate\Support\Facades\Facade;

class Avatax extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'avatax';
    }
}