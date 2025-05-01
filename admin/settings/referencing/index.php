<?php
$access = "../../";
include($access . "../functions/data-base.php");
session_start();

if ($isactivedb != true) {
    header("Location:" . $access . "install");
    die();
}

if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
} else {
    header("Location: " . $access . "login");
    die();
}

$sql = "SELECT * FROM `{$tableprefix}-collection-settings` WHERE `cpropriety` = 'sitename'";
$result = $pdo->query($sql);

if ($result->rowCount() > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if (!empty($row['cvalue'])) {
    } else {
        header("Location:  " . $access . "install/start/");
        die();
    }
} else {
    header("Location: " . $access . "install/start/");
    die();
}

include($access . "translation.php");

$lang = $_SESSION["lang"];
echo '<html lang="' . $lang . '"';

?>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="<?= $access ?>admin-assets/collection.css">
    <link rel="icon" href="<?= $access ?>admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["seo"] ?></title>
</head>

<body>
    <div class="adminpage">
        <?php

        include($access . "../functions/comp-navadmin.php");

        ?>
        <div class="admincontent">
            <main>
                <h1><?= $translation["seo"] ?></h1>

                <h2>Indexation</h2>

                <h3>Règles</h3>
                <p>Pages à ne pas inclure</p>

                <form action="" method="post">

                    <label for="legit">Page à ne pas indexer</label>
                    <p>Écrivez <code>Disallow: /mapage/</code>, une page ou chemin par ligne</p>
                    <textarea id="legit" rows="4" maxlength="500" placeholder="/page1/, /page2/, /page3/"></textarea>
                    <div aria-hidden="true" style="height: 24px;"></div>

                    <input type="submit" value="Save">
                </form>

                <h3>Console</h3>
                <a href="https://www.bing.com/webmasters/about" target="_blank">Console Bing</a>
                <a href="https://search.google.com/search-console?utm_source=about-page" target="_blank">Console Google</a>

                <h3>Site map</h3>
                <p>12 pages, dernière mise à jour 20 mars 2024 </p>
                <button>Copier le lien</button>
                <button>Re générer le sitemap</button>

                <h2>Redirection prenantes</h2>
                <form action="" method="post">

<label for="old">Page à rediriger</label>
<p>Commence par / </p>
<input type="text" id="old" /><div aria-hidden="true" style="height: 8px;"></div>

<label for="new">Nouvelle page</label>
<p>Commence par / ou https</p>
<input type="text" id="new" /><div aria-hidden="true" style="height: 24px;"></div>

<input type="submit" value="Ajouter la redirection">
</form>

                <h2>Rapport</h2>
                <p>Adresse des pages 404, lien cassé, title vide, meta vide</p>

                <h2>Apparence du lien de partage</h2>
                <p>open graph seulement</p>             

            </main>
            <footer>
                Collection 0.4
            </footer>

        </div>
    </div>
</body>

</html>