<?php

include("../../functions/data-base.php");

$mediaId = $_POST['mediaId'];

if (is_numeric($mediaId)) {
    try {

        // Connexion à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Requête pour récupérer les médias
        $sql = "SELECT * FROM `{$tableprefix}-collection-medias` WHERE `id` =  $mediaId";
        $result = $pdo->query($sql);
    
        // Récupération des données
        $medias = $result->fetchAll(PDO::FETCH_ASSOC);
    
        // Renvoi des données au format JSON
        echo json_encode($medias);
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    exit;
}

?>
