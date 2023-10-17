<?php

namespace Modules\User\Http\Controllers;

use Core\Http\Controllers\Controller;

use Modules\User\Contracts\UserService;
use Modules\User\Facades\UserService as UserServiceFacades;
use Modules\User\Http\Requests\RegisterUserRequest;

class UserController extends Controller
{
    /**
     * @var UserService
     */

    /**
     * Create a new instance of the AuthenticationController
     * @param UserService
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }

    public function register(RegisterUserRequest $rquest)
    {
        $validatedData = $rquest->validated();
        return UserServiceFacades::register($validatedData);
    }
}
