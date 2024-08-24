<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\UserModel;
class UserService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getLastUser(): ?array
    {
        return $this->userModel->getLastUser();
    }
}