<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\Comment;
use Camagru\Core\Models\Post;

class CommentService
{
    protected Comment $commentModel;
    protected Post $postModel;
    protected ValidationService $validationService;

    public function __construct()
    {
        $this->commentModel = new Comment();
        $this->postModel = new Post();
        $this->validationService = new ValidationService();
    }

    /**
     * Tente d'ajouter un commentaire.
     *
     * @param int $postId
     * @param string $comment
     * @param string $username
     * @param int $userId
     * @return array ['success' => bool, 'errors' => array]
     */
    public function addComment(int $postId, string $comment, string $username, int $userId): array
    {
        // Réinitialiser les erreurs précédentes
        $this->validationService->resetErrors();

        // Valider le commentaire
        $isCommentValid = $this->validationService->validateComment($comment);

        if (!$isCommentValid) {
            return [
                'success' => false,
                'errors' => $this->validationService->getErrors()
            ];
        }

        // Ajouter le commentaire via le modèle
        $result = $this->commentModel->addComment($postId, $comment, $username, $userId);

        if ($result) {
            return ['success' => true];
        } else {
            $this->validationService->addError('general', "Une erreur est survenue lors de l'ajout du commentaire.");
            return [
                'success' => false,
                'errors' => $this->validationService->getErrors()
            ];
        }
    }

    /**
     * Récupère le propriétaire d'un post.
     *
     * @param int $postId
     * @return array|null
     */
    public function getPostOwner(int $postId): ?array
    {
        return $this->postModel->getPostOwnerById($postId);
    }
}