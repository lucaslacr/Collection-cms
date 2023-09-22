<?= $sql = "SELECT * FROM collectionsetting WHERE id='1'";
$query = $pdo->prepare($sql); // Etape 1 : Préparation de la requête
$query->execute();  // Etape 2 : exécution de la requête
$setting = $query->fetch(); 