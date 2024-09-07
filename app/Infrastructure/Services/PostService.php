<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\PostModel;
class PostService
{
    private $PostModel;

    public function __construct()
    {
        $this->PostModel = new PostModel();
    }
    public function ImageRegister(string $imageContent): ?array
    {
        return $this->PostModel->ImageRegister($imageContent);
    }

    public function GetAllImage(): array
    {
        return $this->PostModel->GetAllImages();
    }
}