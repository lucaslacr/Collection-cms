<?php

include("../../functions/data-base.php");
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file'];
    $filename = $_POST['filename'];
    $csmall = 0;

    // Generate Slug
    $slug = preg_replace('~[^\pL\d]+~u', '-', $filename);
    $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
    $slug = preg_replace('~[^-\w]+~', '', $slug);
    $slug = trim($slug, '-');
    $slug = preg_replace('~-+~', '-', $slug);
    $slug = strtolower($slug);


    // Define file path
    $target_dir = '../../assets/';
    $target_file = $target_dir . basename($slug . '.webp');

    // Check image format
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $valid_extensions = ['jpg', 'jpeg', 'png', 'heif', 'heic'];

    if (in_array($file_extension, $valid_extensions)) {
        $image_size = @getimagesize($file['tmp_name']);

        $image = @imagecreatefromstring(file_get_contents($file['tmp_name']));
        if ($image === false) {
            echo "Error: The uploaded file is not a valid image.";
            exit;
        }

        $maxDimension = 2200;
        $width = imagesx($image);
        $height = imagesy($image);

        // If it's a very large image, resize it
        if ($width > $maxDimension || $height > $maxDimension) {
            $ratio = min($maxDimension / $width, $maxDimension / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            imagedestroy($image);
            $image = $resizedImage; // Use the resized image
        }

        // Convert to WebP
        $target_file = $target_dir . basename($slug . '.webp');
        if (!imagewebp($image, $target_file, 82)) {
            echo "Error: Failed to convert the image to WebP.";
            exit;
        }
        $file_extension = 'webp';

        // If it's not too small, create a small version
        if ($width > 721) {
            $image = @imagecreatefromstring(file_get_contents($file['tmp_name']));
            $ratio = 640 / $width;
            $newWidth = 640;
            $newHeight = (int)($height * $ratio);
            $csmall = 1;

            $smallImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($smallImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            $smallTargetFile = $target_dir . "s/" . basename($slug . '.webp');
            if (!imagewebp($smallImage, $smallTargetFile, 82)) {
                echo "Error: Failed to convert the small image to WebP.";
                exit;
            }
            imagedestroy($smallImage);
        }

        imagedestroy($image);

        echo "File has been saved as WebP.";
    } else {
        // Si le fichier n'est pas une image supportée, le déplacer sans conversion
        $target_file = $target_dir . basename($slug . '.' . $file_extension);
        move_uploaded_file($file['tmp_name'], $target_file);
        echo "Le fichier a été téléchargé sans conversion.";
    }

    // get dimension
    if ($file_extension === 'webp' || $file_extension === 'png' || $file_extension === 'svg' || $file_extension === 'gif' || $file_extension === 'avif') {
        if ($newWidth) {
            $file_width = $newWidth;
            $file_height = $newHeight;
        } else {
            $image_size = getimagesize($file['tmp_name']);
            $file_width = $image_size[0];
            $file_height = $image_size[1];
        }
    } else {
        $file_width = 0;
        $file_height = 0;
    }

    // file info
    $fileaddress = $slug . '.' . $file_extension;
    $file_size = round(filesize($target_file) / 1024, 2);

    switch ($file_extension) {
        case 'webp';
        case 'avif';
        case 'png';
        case 'gif';
        case 'svg';
            $file_type = 'image';
            break;

        case 'pdf';
        case 'docx';
        case 'doc';
        case 'odt';
        case 'txt';
        case 'docx';
        case 'ods';
        case 'xlsx';
        case 'xls';
        case 'pptx';
        case 'ppt';
        case 'pps';
        case 'csv';
            $file_type = 'document';
            break;

        case 'woff2';
        case 'woff';
        case 'ttf';
        case 'otf';
            $file_type = 'font';
            break;

        case 'mp4';
        case 'webm';
        case 'mkv';
        case 'mov';
        case 'avi';
            $file_type = 'video';
            break;

        case 'mp3';
        case 'ogg';
            $file_type = 'audio';
            break;

        case 'vtt';
        case 'srt';
            $file_type = 'subtitle';
            break;

        case 'zip';
        case '7z';
            $file_type = 'compressed files';
            break;

        default;
            $file_type = 'other';
            break;
    }
    $file_alt = $filename;
    $file_owner = $_SESSION["id"];

    $sql = "INSERT INTO `{$tableprefix}-collection-medias` (`id`, `caddress`, `csmall`, `cdate`, `cextension`, `ctype`, `calt`, `csize`, `cheight`, `cwidth`, `cowner`, `cattachments`)
    VALUES (:id, :caddress, :csmall, NOW(), :exten, :ctype, :alt, :csize, :fh, :fw, :conwer, '')";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', 0, PDO::PARAM_INT);
    $stmt->bindValue(':caddress', $fileaddress, PDO::PARAM_STR);
    $stmt->bindValue(':csmall', $csmall, PDO::PARAM_STR);
    $stmt->bindValue(':exten', $file_extension, PDO::PARAM_STR);
    $stmt->bindValue(':ctype', $file_type, PDO::PARAM_STR);
    $stmt->bindValue(':alt', $filename, PDO::PARAM_STR);
    $stmt->bindValue(':csize', $file_size, PDO::PARAM_STR);
    $stmt->bindValue(':fh', $file_height, PDO::PARAM_STR);
    $stmt->bindValue(':fw', $file_width, PDO::PARAM_STR);
    $stmt->bindValue(':conwer', $_SESSION["id"], PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Insertion réussie
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Erreur lors de l'insertion ";
    }

    if (file_exists($target_file)) {
        echo "Le fichier a été téléchargé avec succès.";
    } else {
        echo "Une erreur est survenue lors du téléchargement du fichier.";
    }
}
