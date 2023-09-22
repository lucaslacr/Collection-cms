<?php
include("../functions/data-base.php");
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
    <aside>
       <ul>
        <li><a href="Page">Page</a></li>
        <li><a href="Page">Multimedia</a></li>
        <li><a href="Page">Composant</a></li>
        <li><a href="Page">Setting</a></li>
       </ul>
</aside>
    <main>
        <p>Create is resist, resist is create</p>
    </main>
    <footer>  
     Collection 0.1
</footer>
</body>

</html>