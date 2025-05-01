<?php
include("../../../functions/data-base.php");
session_start();

if ($isactivedb != true) {
    header("Location: install");
    die();
}

if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
} else {
    header("Location: ../../login");
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le contenu brut de la requête
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifiez si les données sont présentes
    if (isset($data['chtml']) && isset($data['id'])) {
        $chtml = $data['chtml']; // Contenu de la div
        $id = $data['id']; // ID

        // Préparer la requête SQL
        $stmt = $pdo->prepare("UPDATE `{$tableprefix}-collection-pages` SET `chtml` = :chtml WHERE `id` = :id");

        // Vérifiez si la préparation a réussi
        if ($stmt) {
            // Lier les paramètres
            $stmt->bindValue(':chtml', $chtml, PDO::PARAM_STR); // Lier le contenu comme une chaîne
            $stmt->bindValue(':id', $id, PDO::PARAM_INT); // Lier l'ID comme un entier

            // Exécuter la requête
            if ($stmt->execute()) {
                echo "Mise à jour réussie.";
            } else {
                echo "Erreur lors de la mise à jour : " . $stmt->errorInfo()[2]; // Afficher l'erreur
            }
        } else {
            echo "Erreur lors de la préparation de la requête : " . $pdo->errorInfo()[2]; // Afficher l'erreur
        }
    } else {
        echo "Les données requises ne sont pas présentes.";
    }

    // Fermer la connexion
    $pdo = null; // Fermer la connexion PDO
} else {
    echo "Requête invalide.";
}
