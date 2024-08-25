<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\LikeModel;
class ProfileService
{
    private $profileModel;

    public function __construct()
    {
        $this->profileModel = new LikeModel();
    }

    public function getLastUser(): ?array
    {
        return $this->profileModel->getLastUser();
    }
}