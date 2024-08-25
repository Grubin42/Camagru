<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\ProfileModel;
class ProfileService
{
    private $profileModel;

    public function __construct()
    {
        $this->profileModel = new ProfileModel();
    }

    public function getLastUser(): ?array
    {
        return $this->profileModel->getLastUser();
    }
}