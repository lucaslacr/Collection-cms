<!doctype html>
<?php
$htmllang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

switch ($htmllang) {
    case "fr":
        echo '<html lang="fr"';
        $lang = "fr";
        break;
    case "en":
        echo '<html lang="en"';
        $lang = "en";
        break;
    case "es":
        echo '<html lang="es"';
        $lang = "es";
        break;
    case "it":
        echo '<html lang="it"';
        $lang = "it";
        break;
    case "de":
        echo '<html lang="de"';
        $lang = "de";
        break;
    case "nl":
        echo '<html lang="nl"';
        $lang = "nl";
        break;
    default:
        echo '<html lang="en"';
        $lang = "en";
};

$translations = array(
    array(
        "lang" => "fr",
        "title" => "Connecter votre base de donnée",
        "description" => "Sassiez dans les champs les informations de votre base de donnée. <br> Vous pouvez les trouver auprès de votre hébergeur web.",
        "namedb" => "Nom de la base de donnée",
        "hostdb" => "Chemin d'accès (host)",
        "hostindication" => "'localhost' dans la plupart des cas",
        "userdbindication" => "Vérifier que l'utilisateur possède les droits d'édition de la base de donnée",
        "userdb" => "Utilisateur de la base de donnée",
        "passworddb" => "Mot de passe de la base de donnée",
        "connect" => "Se connecter à la base de donnée"
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
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../admin-assets/collection.css">
    <link rel="icon" href="../admin-assets/favicon-collection.png" type="image/png">
    <title>Launch Collection website</title>
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
    <main>
        <div class="logo-collection">
            <img class="isdark" src="../admin-assets/logo-collection-d.svg" alt="Collection cms" />
            <img class="islight" src="../admin-assets/logo-collection-l.svg" alt="Collection cms" />
        </div>

        <section class="install-section">

            <?php
            $translation = null;
            foreach ($translations as $t) {
                if ($t["lang"] == $lang) {
                    $translation = $t;
                    break;
                }
            }
            if ($translation != null) {
                echo '<h1>' . $translation["title"] . '</h1> <p>' . $translation["description"] . ' </p>';
                echo '<form action="../start/" method="POST">
        <label for="databasename">' . $translation["namedb"] . '</label>
        <input id="databasename" name="databasename" type="text" required/>

        <label for="databasehost">' . $translation["hostdb"] . '</label>
        <p>' . $translation["hostindication"] . '</p>
        <input id="databasehost" name="databasehost" type="text" value="localhost" required/>

        <div aria-hidden="true" style="height:24px"></div>

        <label for="databaseuser">' . $translation["userdb"] . '</label>
        <p>' . $translation["userdbindication"] . '</p>
        <input id="databaseuser" name="databaseuser" type="text" />

        <label for="databasepassword">' . $translation["passworddb"] . '</label>
        <input id="databasepassword" name="databasepassword" type="password" />
        
        <button type="submit">' . $translation["connect"] . '</button>
    </form>';
            }
            ?>
        </section>
    </main>
</body>

</html>