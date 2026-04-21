<?php
/**
 * ====================================================
 * SCRIPT D'UPLOAD ET DE GESTION DE MÉDIAS
 * ====================================================
 * Gère l'upload, le redimensionnement et la conversion
 * d'images, ainsi que l'upload de fichiers divers.
 */

include("../../functions/data-base.php");
session_start();

// ============================================
// 0. VÉRIFICATIONS PRÉALABLES
// ============================================

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Méthode de requête invalide.']);
    exit;
}

// Vérifier que l'utilisateur est authentifié
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non authentifié.']);
    exit;
}

// Vérifier que les données POST et FILES sont présentes
if (!isset($_FILES['file']) || !isset($_POST['filename'])) {
    echo json_encode(['success' => false, 'error' => 'Données manquantes (fichier ou nom).']);
    exit;
}

$file = $_FILES['file'];
$filename = trim($_POST['filename']);

// Vérifier que le nom de fichier n'est pas vide
if (empty($filename)) {
    echo json_encode(['success' => false, 'error' => 'Le nom de fichier ne peut pas être vide.']);
    exit;
}

// Vérifier les erreurs d'upload
if ($file['error'] !== UPLOAD_ERR_OK) {
    $upload_errors = [
        UPLOAD_ERR_INI_SIZE => 'Le fichier dépasse la limite php.ini.',
        UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la limite du formulaire.',
        UPLOAD_ERR_PARTIAL => 'Le fichier n\'a été que partiellement uploadé.',
        UPLOAD_ERR_NO_FILE => 'Aucun fichier n\'a été uploadé.',
        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant.',
        UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire le fichier sur le disque.',
        UPLOAD_ERR_EXTENSION => 'Extension PHP bloquée.'
    ];
    $error_message = $upload_errors[$file['error']] ?? 'Erreur d\'upload inconnue.';
    echo json_encode(['success' => false, 'error' => $error_message]);
    exit;
}

// Vérifier que le fichier temporaire existe
if (!is_uploaded_file($file['tmp_name'])) {
    echo json_encode(['success' => false, 'error' => 'Le fichier uploadé est invalide.']);
    exit;
}

// Vérifier la taille du fichier (100 MB max)
$max_file_size = 100 * 1024 * 1024; // 100 MB
if ($file['size'] > $max_file_size) {
    echo json_encode(['success' => false, 'error' => 'Le fichier est trop volumineux (max 100 MB).']);
    exit;
}

// ============================================
// 1. GÉNÉRER LE SLUG
// ============================================
$slug = preg_replace('~[^\pL\d]+~u', '-', $filename);
$slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
$slug = preg_replace('~[^-\w]+~', '', $slug);
$slug = trim($slug, '-');
$slug = preg_replace('~-+~', '-', $slug);
$slug = strtolower($slug);

// Vérifier que le slug n'est pas vide
if (empty($slug)) {
    echo json_encode(['success' => false, 'error' => 'Le nom de fichier est invalide après traitement.']);
    exit;
}

// ============================================
// 2. DÉFINIR LES CHEMINS
// ============================================
$target_dir = '../../assets/';
$small_dir = $target_dir . 's/';

// Vérifier que le répertoire principal est accessible en écriture
if (!is_writable($target_dir)) {
    echo json_encode(['success' => false, 'error' => 'Le répertoire d\'upload n\'est pas accessible en écriture.']);
    exit;
}

// Créer le répertoire des images réduites s'il n'existe pas
if (!is_dir($small_dir)) {
    if (!mkdir($small_dir, 0755, true)) {
        echo json_encode(['success' => false, 'error' => 'Impossible de créer le répertoire des images réduites.']);
        exit;
    }
}

// Vérifier que le répertoire des images réduites est accessible en écriture
if (!is_writable($small_dir)) {
    echo json_encode(['success' => false, 'error' => 'Le répertoire des images réduites n\'est pas accessible en écriture.']);
    exit;
}

$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

// ============================================
// 3. DÉTERMINER LE TYPE DE FICHIER
// ============================================
$image_extensions = ['jpg', 'jpeg', 'png', 'heif', 'heic', 'bmp', 'gif', 'webp', 'avif', 'svg'];
$is_image = in_array($file_extension, $image_extensions);

$file_type = 'other';
$file_width = 0;
$file_height = 0;
$csmall = 0;

if ($is_image) {
    $file_type = 'image';
} elseif (in_array($file_extension, ['pdf', 'docx', 'doc', 'odt', 'txt', 'ods', 'xlsx', 'xls', 'pptx', 'ppt', 'pps', 'csv'])) {
    $file_type = 'document';
} elseif (in_array($file_extension, ['woff2', 'woff', 'ttf', 'otf'])) {
    $file_type = 'font';
} elseif (in_array($file_extension, ['mp4', 'webm', 'mkv', 'mov', 'avi'])) {
    $file_type = 'video';
} elseif (in_array($file_extension, ['mp3', 'ogg', 'wav'])) {
    $file_type = 'audio';
} elseif (in_array($file_extension, ['vtt', 'srt'])) {
    $file_type = 'subtitle';
} elseif (in_array($file_extension, ['zip', '7z', 'rar'])) {
    $file_type = 'compressed files';
}

$target_file = null;

// ============================================
// 4. TRAITER LES IMAGES
// ============================================
if ($is_image) {
    // Charger l'image
    
    $image = imagecreatefromstring(file_get_contents($file['tmp_name']));
    if ($image === false) {
        echo json_encode(['success' => false, 'error' => 'Le fichier uploadé n\'est pas une image valide.']);
        exit;
    }

    $original_width = imagesx($image);
    $original_height = imagesy($image);

    // Vérifier les dimensions
    if ($original_width <= 0 || $original_height <= 0) {
        imagedestroy($image);
        echo json_encode(['success' => false, 'error' => 'Les dimensions de l\'image sont invalides.']);
        exit;
    }

    // ============================================
    // 4.1 REDIMENSIONNER SI PLUS GRAND QUE 2600x2600
    // ============================================
    $max_dimension = 2600;
    if ($original_width > $max_dimension || $original_height > $max_dimension) {
        $ratio = min($max_dimension / $original_width, $max_dimension / $original_height);
        $new_width = (int)($original_width * $ratio);
        $new_height = (int)($original_height * $ratio);

        $resized_image = imagecreatetruecolor($new_width, $new_height);
        if ($resized_image === false) {
            imagedestroy($image);
            echo json_encode(['success' => false, 'error' => 'Erreur lors du redimensionnement de l\'image.']);
            exit;
        }

        if (!imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height)) {
            imagedestroy($image);
            imagedestroy($resized_image);
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la copie redimensionnée de l\'image.']);
            exit;
        }

        imagedestroy($image);
        $image = $resized_image;

        $file_width = $new_width;
        $file_height = $new_height;
    } else {
        $file_width = $original_width;
        $file_height = $original_height;
    }

    // ============================================
    // 4.2 CONVERTIR EN WEBP
    // ============================================
    $target_file = $target_dir . $slug . '.webp';

    // Vérifier si le fichier existe déjà et le supprimer
    if (file_exists($target_file)) {
        if (!unlink($target_file)) {
            imagedestroy($image);
            echo json_encode(['success' => false, 'error' => 'Impossible de remplacer le fichier existant.']);
            exit;
        }
    }

    if (!imagewebp($image, $target_file, 82)) {
        imagedestroy($image);
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la conversion en WebP.']);
        exit;
    }

    $file_extension = 'webp';

    // ============================================
    // 4.3 CRÉER UNE VERSION RÉDUITE SI NÉCESSAIRE
    // ============================================
    if ($file_width > 721) {
        $small_ratio = 640 / $file_width;
        $small_width = 640;
        $small_height = (int)($file_height * $small_ratio);

        // S'assurer que les dimensions sont valides
        if ($small_height <= 0) {
            $small_height = 1;
        }

        $small_image = imagecreatetruecolor($small_width, $small_height);
        if ($small_image === false) {
            imagedestroy($image);
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la création de la version réduite.']);
            exit;
        }

        if (!imagecopyresampled($small_image, $image, 0, 0, 0, 0, $small_width, $small_height, $file_width, $file_height)) {
            imagedestroy($image);
            imagedestroy($small_image);
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la copie de la version réduite.']);
            exit;
        }

        $small_target_file = $small_dir . $slug . '.webp';

        // Vérifier si le fichier existe déjà et le supprimer
        if (file_exists($small_target_file)) {
            if (!unlink($small_target_file)) {
                imagedestroy($image);
                imagedestroy($small_image);
                echo json_encode(['success' => false, 'error' => 'Impossible de remplacer la version réduite existante.']);
                exit;
            }
        }

        if (!imagewebp($small_image, $small_target_file, 82)) {
            imagedestroy($image);
            imagedestroy($small_image);
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la création de la version réduite en WebP.']);
            exit;
        }

        imagedestroy($small_image);
        $csmall = 1;
    }

    // Libérer la ressource image
    imagedestroy($image);

} else {
    // ============================================
    // 5. TRAITER LES FICHIERS NON-IMAGE
    // ============================================
    $target_file = $target_dir . $slug . '.' . $file_extension;

    // Vérifier si le fichier existe déjà et le supprimer
    if (file_exists($target_file)) {
        if (!unlink($target_file)) {
            echo json_encode(['success' => false, 'error' => 'Impossible de remplacer le fichier existant.']);
            exit;
        }
    }

    if (!move_uploaded_file($file['tmp_name'], $target_file)) {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'upload du fichier.']);
        exit;
    }
}

// ============================================
// 6. RÉCUPÉRER LES INFORMATIONS DU FICHIER
// ============================================
if (!file_exists($target_file)) {
    echo json_encode(['success' => false, 'error' => 'Le fichier n\'a pas été sauvegardé correctement.']);
    exit;
}

$fileaddress = $slug . '.' . $file_extension;
$file_size = round(filesize($target_file) / 1024, 2);

// ============================================
// 7. INSÉRER DANS LA BASE DE DONNÉES
// ============================================
try {
    $sql = "INSERT INTO `{$tableprefix}-collection-medias` 
            (`caddress`, `csmall`, `cdate`, `cextension`, `ctype`, `calt`, `csize`, `cheight`, `cwidth`, `cowner`, `cattachments`)
            VALUES (:caddress, :csmall, NOW(), :exten, :ctype, :alt, :csize, :fh, :fw, :cowner, '')";

    $stmt = $pdo->prepare($sql);

    // Vérifier que la préparation a réussi
    if ($stmt === false) {
        throw new Exception('Erreur lors de la préparation de la requête SQL.');
    }

    $stmt->bindValue(':caddress', $fileaddress, PDO::PARAM_STR);
    $stmt->bindValue(':csmall', $csmall, PDO::PARAM_INT);
    $stmt->bindValue(':exten', $file_extension, PDO::PARAM_STR);
    $stmt->bindValue(':ctype', $file_type, PDO::PARAM_STR);
    $stmt->bindValue(':alt', $filename, PDO::PARAM_STR);
    $stmt->bindValue(':csize', $file_size, PDO::PARAM_STR);
    $stmt->bindValue(':fh', $file_height, PDO::PARAM_INT);
    $stmt->bindValue(':fw', $file_width, PDO::PARAM_INT);
    $stmt->bindValue(':cowner', $_SESSION["id"], PDO::PARAM_INT);

    if ($stmt->execute()) {
        $mediaId = $pdo->lastInsertId();
        echo json_encode([
            'success' => true,
            'id' => $mediaId,
            'message' => 'Le fichier a été téléchargé avec succès.',
            'file' => $fileaddress,
            'width' => $file_width,
            'height' => $file_height,
            'size' => $file_size,
            'type' => $file_type
        ]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion en base de données : ' . $errorInfo[2]]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Exception : ' . $e->getMessage()]);
}
?>