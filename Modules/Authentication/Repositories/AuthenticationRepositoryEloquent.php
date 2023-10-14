<?php

namespace Modules\Authentication\Repositories;

use Core\Repositories\Repository;
use Modules\Authentication\Contracts\AuthenticationRepository;
use Modules\Authentication\Models\Authentication;

class AuthenticationRepositoryEloquent extends Repository implements AuthenticationRepository
{
    /**
     * Create a new instance of the repository.
     * 
     * @return void
     */
    public function __construct(Authentication $entity)
    {
        parent::__construct($entity);
    }
}
