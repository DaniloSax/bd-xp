<?php

namespace App\Modules\User\Http\Controllers;

use App\Core\BaseController;
use App\Modules\User\Domain\Requests\UserRequest;
use App\Modules\User\Domain\Services\UserService;
use App\Modules\User\Http\Resources\UserResource;

class UserController extends BaseController
{

    public function __construct()
    {
        $this->setDependences(
            service: UserService::class,
            formRequest: UserRequest::class,
            resource: UserResource::class
        );
    }
}
