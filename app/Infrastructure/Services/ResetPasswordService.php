<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\UserModel;

class ResetPasswordService
{
    private $UserModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
    }

    public function resetPassword(int $id,string $newPassword)
    {
        return $this->UserModel->resetPassword($id, $newPassword);
    }
}