<?php
include("../functions/data-base.php");
session_start();

if ($isactivedb != true) {
    header("Location: install");
    die();
}

if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
} else {
    header("Location: ./login");
    die();
}

$sql = "SELECT `c-name` FROM `{$tableprefix}-collection-settings` LIMIT 1";
$result = $pdo->query($sql);

if ($result->rowCount() > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if (!empty($row['c-name'])) {
    } else {
        header("Location: ./install/start/");
        die();
    }
} else {
    header("Location: ./install/start/");
    die();
}

$lang = $_SESSION["lang"];
echo '<html lang="' . $lang . '"';

$translations = array(
    array(
        "lang" => "fr",
        "title" => "Modifier le site",
        "page" => "Pages",
        "media" => "Médias",
        "announcement" => "Annonces",
        "setting" => "Paramètres",
        "component" => "Composants",
        "form" => "Formulaires"
    ),
    array(
        "lang" => "en",
        "title" => "Connect to your database",
        "description" => "Fill the form with your database information. <br> You can find them with our web hoster.",
        "namedb" => "Database name",
        "hostdb" => "Host (address)",
        "hostindication" => "'localhost' in most of case",
        "userdbindication" => "Check if your database user have the right to edit database",
        "userdb" => "Database user",
        "passworddb" => "Database password",
        "connect" => "Connect to database"
    )
);
$translation = null;
foreach ($translations as $t) {
    if ($t["lang"] == $lang) {
        $translation = $t;
        break;
    }
}
?>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="./admin-assets/collection.css">
    <link rel="icon" href="./admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["title"] ?></title>
    <style>
        .install-section {
            padding: 24px;
            border-radius: 8px;
            background-color: var(--surface-secondary);
            margin-top: 24px;
            max-width: 820px;
        }

        .install-section h1 {
            font-size: 32px;
        }

        .logo-collection img {
            display: block;
            margin: 64px auto;
            max-height: 64px;
        }

        nav.admin li a {
            display: flex;
            gap: 6px;
            flex-direction: row;
            border-bottom: 0;
            align-items: center;
        }

        nav.admin ul {
            display: flex;
            gap: 12px;
            flex-direction: column;
        }

        .icon-admin img {
            height: 28px;
            width: 28px;
            margin: 0px;
        }

        nav.admin li::marker {
            content: '';
        }

        .adminpage {
            display: flex;
            flex-direction: row;
            max-width: 1200px;
            gap: 40px;
            width: 100%;
            margin: 0 auto;
        }

        .adminpage header {
            display: flex;
            flex-direction: column;
            max-width: 280px;
        }

        .adminpage .logo-collection img {
            display: block;
            margin: 4px 4px 40px 0px;
            max-height: 40px;
        }

        .admincontent {
            width: 100%;
            padding: 24px 0;
        }

        .islight {
            display: block;
        }

        .isdark {
            display: none !important;
        }

        @media (prefers-color-scheme: dark) {

            .islight {
                display: none !important;
            }

            .isdark {
                display: block !important;
            }
        }
    </style>
</head>

<body>
    <div class="adminpage">
    <?php
       $access ="./";
       include("../functions/comp-navadmin.php");
       
       ?>
        <div class="admincontent">
            <main>
                <p>Here the content</p>
                <p><?= $row["c-site-name"]; ?>
            </main>
            <footer>
                Collection 0.4
            </footer>

        </div>
    </div>
</body>

</html>