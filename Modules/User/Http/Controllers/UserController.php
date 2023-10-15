<?php

namespace Modules\User\Http\Controllers;

use Core\Http\Controllers\Controller;

use Modules\User\Contracts\UserService;
use Modules\User\Http\Requests\RegisterUserRequest;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $service;

    /**
     * Create a new instance of the AuthenticationController
     * @param UserService
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }

    public function register(RegisterUserRequest $rquest)
    {
        $validatedData = $rquest->validated();
        return $this->service->register($validatedData);
    }
}
