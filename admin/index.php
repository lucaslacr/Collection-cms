<?php
        include("../functions/data-base.php");
        
        $sql = "SELECT * FROM collectionsetting";
        $query = $pdo->prepare($sql); // Etape 1 : Préparation de la requête
        $query->execute();  // Etape 2 : exécution de la requête
    
        $rowCount = $query->fetchColumn();

        if ($rowCount > 0) {
           
        } else {
            header("Location: install");
            die();
        }
?>
<?php
include("../functions/get-setting.php");
?>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="UTF-8">
    <title>Collection - <?= $setting['sitename'] ?></title>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../elements/favicon.png" />
    <meta name="theme-color" content="#510cd1">
    <meta property="og:image" content="../elements/icon.png" />
    <link rel="stylesheet" type="text/css" href="../elements/design.css" />
</head>

<body>
    <nav>
       <ul>
        <li><a href="Page">Page</a></li>
        <li><a href="Page">Media</a></li>
        <li><a href="Page">Composant</a></li>
        <li><a href="Page">Form</a></li>
        <!--
        <li><a href="Page">Order</a></li>
        <li><a href="Page">Product</a></li>
        <li><a href="Page">Ecom setting</a></li>
         -->
        <li><a href="Page">Setting</a></li>
       </ul>
</nav>
    <main>
        <p>Here the content</p>
    </main>
    <footer>  
     Collection 0.1
</footer>
</body>

</html>