<?php

namespace Sayed\SayedDashboard\Facades;

use Illuminate\Support\Facades\Facade;

class SayedDashboard extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sayed-dashboard';
    }
}