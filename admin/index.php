<?php
        include("../functions/data-base.php");
        
        $sql = "SELECT * FROM collectionsetting";
        $query = $pdo->prepare($sql); // Etape 1 : Préparation de la requête
        $query->execute();  // Etape 2 : exécution de la requête
    
        $rowCount = $query->fetchColumn();

        if ($rowCount > 0) {
            include("homecollection.php");
        } else {
            include("onboardingcollection.php");
        }
?>