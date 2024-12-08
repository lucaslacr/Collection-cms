<?= $sql = "SELECT * FROM collectionsetting WHERE id='1'";
$query = $pdo->prepare($sql); // Etape 1 : Préparation de la requête
$query->execute();  // Etape 2 : exécution de la requête
$setting = $query->fetch(); 


$sql = "SELECT `c-site-name` FROM `{$tableprefix}-colletion-settings` LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Aucune donnée trouvée dans la table 'site-settings'.";
    }