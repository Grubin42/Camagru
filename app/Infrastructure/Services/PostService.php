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
        return $this->PostModel->getAllImages();
    }
    
    public function mergeAndSaveImage($photoData, $stickerData): bool {
        // Décoder l'image capturée en base64
        $photoData = str_replace('data:image/png;base64,', '', $photoData);
        $photoData = base64_decode($photoData);
        
        if (!$photoData) {
            echo "Erreur lors du décodage de l'image capturée.";
            return false;
        }
    
        // Charger l'image capturée
        $photoImage = imagecreatefromstring($photoData);
        if (!$photoImage) {
            echo "Erreur lors de la création de l'image capturée.";
            return false;
        }
    
        // Charger l'image overlay (sticker)
        $stickerData = str_replace('data:image/png;base64,', '', $stickerData);
        $stickerData = base64_decode($stickerData);
    
        if (!$stickerData) {
            echo "Erreur lors du décodage du sticker.";
            return false;
        }
    
        $stickerImageResource = imagecreatefromstring($stickerData);
        if (!$stickerImageResource) {
            echo "Erreur lors de la création de l'image overlay.";
            return false;
        }
    
        // Obtenir les dimensions des deux images
        $photoWidth = imagesx($photoImage);
        $photoHeight = imagesy($photoImage);
        $stickerWidth = imagesx($stickerImageResource);
        $stickerHeight = imagesy($stickerImageResource);
    
        // Debug: Afficher les dimensions de la photo et du sticker
        // echo "Dimensions de la photo: largeur = $photoWidth, hauteur = $photoHeight<br>";
        // echo "Dimensions du sticker: largeur = $stickerWidth, hauteur = $stickerHeight<br>";
    
        // Redimensionner le sticker (par exemple à 50% de sa taille originale)
        $newStickerWidth = $stickerWidth * 0.5; // 50% de la largeur originale
        $newStickerHeight = $stickerHeight * 0.5; // 50% de la hauteur originale
    
        // Créer une nouvelle ressource d'image pour le sticker redimensionné
        $resizedSticker = imagecreatetruecolor($newStickerWidth, $newStickerHeight);
    
        // Maintenir la transparence du sticker si c'est un PNG avec de la transparence
        imagealphablending($resizedSticker, false);
        imagesavealpha($resizedSticker, true);
    
        // Redimensionner le sticker
        imagecopyresampled(
            $resizedSticker,          // Destination (nouvelle image)
            $stickerImageResource,    // Source (image d'origine)
            0, 0,                     // Coordonnées de la destination
            0, 0,                     // Coordonnées de la source
            $newStickerWidth,         // Largeur redimensionnée
            $newStickerHeight,        // Hauteur redimensionnée
            $stickerWidth,            // Largeur originale
            $stickerHeight            // Hauteur originale
        );
    
        // Fusionner les images : placer le sticker redimensionné par-dessus l'image capturée
        // On peut ajuster la position selon les besoins (ici, en bas à droite avec un décalage de 10px)
        $x = $photoWidth - $newStickerWidth - 10; // Position en x (10px de marge à droite)
        $y = $photoHeight - $newStickerHeight - 10; // Position en y (10px de marge en bas)
        imagecopy($photoImage, $resizedSticker, $x, $y, 0, 0, $newStickerWidth, $newStickerHeight);
    
        // Enregistrer l'image fusionnée en mémoire
        ob_start();
        imagepng($photoImage); // Convertir en PNG
        $mergedImageData = ob_get_clean(); // Récupérer les données binaires
    
        if (!$mergedImageData) {
            echo "Erreur lors de la fusion de l'image.";
            return false;
        }
    
        // Sauvegarder l'image en base de données via le modèle
        $result = $this->PostModel->saveImageToDatabase($mergedImageData);
    
        // Libérer les ressources mémoire
        imagedestroy($photoImage);
        imagedestroy($resizedSticker);
        imagedestroy($stickerImageResource);
    
        return $result;
    }
    public function GetMyImage(): array
    {
        return $this->PostModel->getImagesByUserId();
    }

    public function GetPostsPaginated($postsPerPage, $offset)
    {
        return $this->PostModel->GetPostsPaginated($postsPerPage,$offset);
    }
    public function GetTotalPosts()
    {
        return $this->PostModel->GetTotalPosts();
    }

    public function deletePhoto($postId, $userId)
    {
        return $this->PostModel->deletePost($postId,$userId);
    }

    public function ValidatePost($photoData, $stickerData, $maxImageSize)
    {
        $errors = [];
        if (empty($photoData)) {
            $errors = "Erreur : aucune photo sélectionnée.";
        } else {

            $base64Prefix = substr($photoData, 0, strpos($photoData, ','));

            // Vérifier le type d'image avec les préfixes spécifiques
            if (strpos($base64Prefix, 'data:image/png') === false && strpos($base64Prefix, 'data:image/jpeg') === false) {
                $errors[] = "Erreur : seuls les fichiers PNG et JPEG sont autorisés.";
            }

            // Calculer la taille réelle de l'image décodée à partir du base64
            $photoSizeInBytes = (int)(strlen(base64_decode(explode(',', $photoData)[1])));
            
            // Vérifier la taille de la photo
            if ($photoSizeInBytes > $maxImageSize) {
                $errors[] = "Erreur : la taille de la photo dépasse la limite autorisée de 5 Mo.";
            }
        }

        // Vérifier si un sticker a bien été sélectionné
        if (empty($stickerData)) {
            $errors[] = "Erreur : aucun sticker sélectionné.";
        }
        return $errors;
    }
}