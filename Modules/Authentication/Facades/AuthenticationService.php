<?php

namespace Modules\Authentication\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Authentication\Contracts\AuthenticationService as AuthenticationServiceContract;

class AuthenticationService extends Facade
{
    /**
     * Get the registered name of the component
     * 
     * @return string
     */

    protected static function getFacadeAccessor()
    {
        return AuthenticationServiceContract::class;
    }
}
