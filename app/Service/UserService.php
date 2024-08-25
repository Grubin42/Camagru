<?php

namespace Camagru\Service;
use Camagru\Core\Models\User;

class UserService {

    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function getUserById(int $id): ?array {
        return $this->userModel->getUserById($id);
    }
}