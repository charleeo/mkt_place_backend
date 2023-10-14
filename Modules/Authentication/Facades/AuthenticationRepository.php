<?php

namespace Modules\Authentication\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Authentication\Contracts\AuthenticationRepository as AuthenticationRepositoryContract;

class AuthenticationRepository extends Facade
{
    /**
     * Get the registered name of the component
     * 
     * @return string
     */

    protected static function getFacadeAccessor()
    {
        return AuthenticationRepositoryContract::class;
    }
}
