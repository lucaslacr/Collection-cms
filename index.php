<?php
include("functions/data-base.php");

if ($isactivedb == false) {
    echo "Le site n'est pas encore installé";
    exit;
}

$url = $_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);
$lastSlashPos = strrpos($path, '/');

if ($lastSlashPos !== false) {
    $pathPart = substr($path, 0, $lastSlashPos);
    $slug = substr($path, $lastSlashPos + 1);

    if (empty($pathPart)) {
        $pathPart = '/';
    }

    $stmt = $pdo->prepare("SELECT * FROM `{$tableprefix}-collection-pages` WHERE `cslug` = :cslug AND `cpath` = :cpath AND `cvisitoracess` = 1");
    $stmt->bindParam(':cslug', $slug);
    $stmt->bindParam(':cpath', $pathPart);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        global $row;
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Erreur 404 : Page non trouvée.";
        exit;
    }
} else {
    echo "Aucun chemin trouvé.";
    exit;
}
include("components/topinfo.php");
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="admin/admin-assets/collection.css">
    <link rel="icon" href="admin/admin-assets/favicon-collection.png" type="image/png">
    <title><?= $row['ctitle'] ?></title>
    <meta name="description" content="<?= $row['cdescription'] ?>">
</head>

<body>
    <?php
    

    echo $row['chtml'];

    // Gpc for cookies banner
    if (isset($_SERVER['HTTP_SEC_GPC'])) {
        $gpc = $_SERVER['HTTP_SEC_GPC'];
        if ($gpc === '1') {
            echo "<dialog id='dialog' open=''>
        <h4>Consentez-vous au traitement de vos données ?</h4>
        <p>Nous utilisons des cookies pour le bon fonctionnent du site : </p>
        <button>Accepter les cookies</button>
        <form method='dialog'>
            <button>Refuser</button>
        </form>
        <button aria-haspopup='dialog' aria-controls='cookieschoice' onclick=`'this.closest(\"dialog\").close()';document.getElementById('cookieschoice').showModal();\">Choisir</button>
    </dialog>
    
    <dialog id='cookieschoice'>
        <h4>Choisissez</h4>
        <p>Nous utilisons des cookies pour le bon fonctionnent du site : </p>
        <form>
        <input type='checkbox' role='switch' id='fonctionnel'>
        <label for='fonctionnel'> Fonctionnel</label>
            <button>Enregistrer</button>
        </form>
        <button aria-haspopup='dialog' onclick='this.closest(\"dialog\").close()'>Fermer</button>
    </dialog>
        ";
        } else {
            echo "Global Privacy Control n'est pas activé.";
        }
    } else {
        echo "L'en-tête Global Privacy Control n'est pas présent.";
    }



    session_start();
    if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
        echo '
    <div class="editpagecontent"> 
    <div class="editpage"> 
       <a href="/admin/pages/edit/?p=' . $row['id'] . '"><img src="admin/admin-assets/edit-l.svg" alt>Modifier</a>
       <div style="width: 2px; height: 100%; background-color: #ddd;"></div>
       <a href="/admin"><img src="admin/admin-assets/dashboard-l.svg" alt="Tableau de bord"></a>
    </div>
    </div>
    <style>
    .editpagecontent {
        display: flex;
        position:fixed;
        bottom:16px;
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
    }
    .editpage {
        display: flex;
        padding: 8px 16px;
        border-radius: 99px; 
        gap: 8px;
        background-color: #ccc;
        margin-left: auto;
    }
    .editpage a {
       text-decoration: none;
       border-bottom: 0; 
       display: flex;
       align-items: center;
    }
    .editpage img {
        height: 24px;
        width: 24px;
    }
    </style>
    ';
    }
    ?>

</body>

</html>