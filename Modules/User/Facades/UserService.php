<?php

namespace Modules\User\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\User\Contracts\UserService as UserServiceContract;

class UserService extends Facade
{
    /**
     * Get the registered name of the component
     * 
     * @return string
     */

    protected static function getFacadeAccessor()
    {
        return UserServiceContract::class;
    }
}
