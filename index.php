<?php
include("functions/data-base.php");
include("functions/get-setting.php");

$sql = "SELECT * FROM collectionpage WHERE id='1'";
$query = $pdo->prepare($sql); // Etape 1 : Préparation de la requête
$query->execute();  // Etape 2 : exécution de la requête
$page = $query->fetch();

?>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="UTF-8">
    <title><?= $page['title'] ?> - <?= $setting['sitename'] ?></title>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../elements/favicon.png" />
    <meta name="theme-color" content="#510cd1">
    <meta property="og:image" content="../elements/icon.png" />
    <link rel="stylesheet" type="text/css" href="../elements/design.css" />
</head>

<body>
    <header>
        <?= include("composant/header.php"); ?>
    </header>
    <main>
        <?= $page['content'] ?>
    </main>
    <footer>  
       <?= include("composant/footer.php"); ?>
</footer>
</body>

</html>