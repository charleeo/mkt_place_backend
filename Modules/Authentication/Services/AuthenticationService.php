<?php

namespace Modules\Authentication\Services;

use Core\Services\Service;
use Modules\Authentication\Contracts\AuthenticationRepository;
use Modules\Authentication\Contracts\AuthenticationService as AuthenticationServiceContract;

class AuthenticationService extends Service implements AuthenticationServiceContract
{
    /**
     * Create new instance of DeveloperService
     *
     * @param AuthenticationRepository $repository
     * @return void
     */
    public function __construct(AuthenticationRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Create a user
     */
    public function register()
    {
        return $this->store(["name" => "Charles"]);
    }
}
