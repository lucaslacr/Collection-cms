<?=
$sql = "SELECT * FROM collectionpage WHERE id='1'";
$query = $pdo->prepare($sql); // Etape 1 : Préparation de la requête
$query->execute();  // Etape 2 : exécution de la requête
$page = $query->fetch();