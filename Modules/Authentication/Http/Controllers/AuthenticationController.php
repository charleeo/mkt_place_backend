<?php

namespace Modules\Authentication\Http\Controllers;

use Core\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Authentication\Contracts\AuthenticationService;
use Modules\Authentication\Http\Requests\LoginValidationRequest;

class AuthenticationController extends Controller
{
    protected $service;
    /**
     * @var AuthenticationService
     */


    /**
     * Create a new instance of the AuthenticationController
     * @param AuthenticationService
     */
    public function __construct(AuthenticationService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }

    public function login(LoginValidationRequest $request)
    {
        return $this->service->login($request);
    }
}
