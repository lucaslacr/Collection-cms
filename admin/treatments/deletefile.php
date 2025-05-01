<?php

include("../../functions/data-base.php");

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier si l'ID est présent dans les données
if (isset($data['id']) && isset($data['slug'])) {
    $mediaId = $data['id'];
    $slug = $data['slug'];

    // Vérifier si l'ID est numérique
    if (is_numeric($mediaId)) {
        try {
            // Connexion à la base de données
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Chemins des fichiers à supprimer
            $files = [
                '../../assets/' . $slug,
                '../../assets/s/' . $slug
            ];
            
            // Supprimer les fichiers s'ils existent
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                } else {
                    // Fichier non trouvé, vous pouvez enregistrer un message de log si nécessaire
                }
            }

            // Requête pour supprimer le média de la base de données
            $sql = "DELETE FROM `{$tableprefix}-collection-medias` WHERE `id` = :mediaId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':mediaId', $mediaId, PDO::PARAM_INT);
            $stmt->execute();

            // Répondre avec un message de succès
            echo json_encode(['message' => 'Fichier supprimé avec succès.']);
        } catch (PDOException $e) {
            // Gérer les erreurs de base de données
            echo json_encode(['error' => 'Erreur de base de données: ' . $e->getMessage()]);
        } catch (Exception $e) {
            // Gérer d'autres erreurs
            echo json_encode(['error' => 'Erreur: ' . $e->getMessage()]);
        }
    } else {
        // Répondre avec une erreur si l'ID n'est pas numérique
        echo json_encode(['error' => 'L\'ID de fichier doit être numérique.']);
    }
} else {
    // Répondre avec une erreur si l'ID ou le format est manquant
    echo json_encode(['error' => 'ID de fichier ou format manquant.']);
}
