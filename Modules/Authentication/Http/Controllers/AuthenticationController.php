<?php

namespace Modules\Authentication\Http\Controllers;

use Core\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Authentication\Contracts\AuthenticationService;
use Modules\Authentication\Facades\AuthenticationService as AuthenticationServiceFacades;
use Modules\Authentication\Http\Requests\LoginValidationRequest;
use Modules\Authentication\Http\Requests\ResetPasswordLinkValidationRequest;
use Modules\Authentication\Http\Requests\ResetPasswordValidationRequest;

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
    }

    public function login(LoginValidationRequest $request)
    {
        return AuthenticationServiceFacades::login($request);
    }

    public function sendResetLink(ResetPasswordLinkValidationRequest $request)
    {
        return AuthenticationServiceFacades::sendPasswordResetEmail($request);
    }

    public function resetPassword(Request $request)
    {
        return AuthenticationServiceFacades::resetPassword($request);
    }
}
