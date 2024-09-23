<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;
class ProfileService
{
    private $profileModel;

    public function __construct()
    {
        $this->profileModel = new User();
    }

    public function getLastUser(): ?array
    {
        return $this->profileModel->getLastUser();
    }

    public function getUsername($id): ?string
    {
        $user = $this->profileModel->getUserById($id);
        return $user['username'];
    }

    public function updateUsername($id, $username): void
    {
        $this->profileModel->updateUsername($id, $username);
    }

    public function getPassword($id): ?string
    {
        $user = $this->profileModel->getUserById($id);
        return $user['password'];
    }

    public function updatePassword($id, $current, $password, $confirmation): void
    {
        $currentHashedDbPassword = $this->getPassword($id);
        if (!password_verify($current, $currentHashedDbPassword)) {
            throw new \Exception('Le mot de passe actuel est incorrect.');
        }
        
        if ($password !== $confirmation) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        $this->profileModel->updatePassword($id, $password);
    }
}