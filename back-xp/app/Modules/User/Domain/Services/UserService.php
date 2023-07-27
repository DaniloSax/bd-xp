<?php

namespace App\Modules\User\Domain\Services;

use App\Core\BaseService;
use App\Modules\User\Domain\Models\User;
use App\Modules\User\Domain\Repositories\UserRepository;

class UserService extends BaseService
{
    public function __construct()
    {
        $this->setRepository(
            repository: UserRepository::class,
            model: User::class
        );
    }
}
