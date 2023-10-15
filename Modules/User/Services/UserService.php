<?php

namespace Modules\User\Services;

use Core\Services\Service;
use Illuminate\Support\Facades\Hash;
use Modules\User\Contracts\UserRepository;
use Modules\User\Contracts\UserService as ContractsUserService;
use Modules\User\Http\Requests\RegisterUserRequest;

class UserService extends Service implements ContractsUserService
{
    /**
     * Create new instance of DeveloperService
     *
     * @param UserRepository $repository
     * @return void
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Create a user
     */
    public function register($data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->store($data);
        return response()->json(["data" => $user]);
    }
}
