<?php

namespace Modules\Authentication\Http\Controllers;

use Core\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Authentication\Contracts\AuthenticationService;

class AuthenticationController extends Controller
{
    /**
     * @var AuthenticationService
     */


    /**
     * Create a new instance of the AuthenticationController
     * @param AuthenticationService
     */
    public function __construct(AuthenticationService $service)
    {
        info("Service", [$service]);
        parent::__construct($service);
    }
}
