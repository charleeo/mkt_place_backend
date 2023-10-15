<?php

namespace Modules\User\Repositories;

use App\Models\User;
use Core\Repositories\Repository;


use Modules\User\Contracts\UserRepository;

class UserRepositoryEloquent extends Repository implements UserRepository
{
    /**
     * Create a new instance of the repository.
     * 
     * @return void
     */
    public function __construct(User $entity)
    {
        parent::__construct($entity);
    }
}
