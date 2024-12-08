<!doctype html>
<?php

include("../../functions/data-base.php");

if ($isactivedb == true) {
    header("Location: ./create-admin/");
    die();
}

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
    case "pt":
        echo '<html lang="pt"';
        $lang = "pt";
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
        "connect" => "Se connecter à la base de donnée",
        "failconnection" => "La connection à échouée",
        "retry" => "Réessayer avec d'autres identifiants",
        "error" => "Erreur : ",
        "errorfile" => "Une erreur est survenue lors de la création du fichier.",
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

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../admin-assets/collection.css">
    <link rel="icon" href="../admin-assets/favicon-collection.png" type="image/png">
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

            // try database connection
            if (isset($_POST["databasename"])) {

                $host = $_POST["databasehost"];
                $db = $_POST["databasename"];
                $user = $_POST["databaseuser"];
                $passwd = $_POST["databasepassword"];

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (Exception $e) {
                    echo "<h1>" . $translation['failconnection'] . "</h1>";
                    echo "<p>";
                    echo $translation['error'] . $e->getMessage() . "<br />";
                    echo "</p>";
                    echo "<a href='./'>". $translation['retry'] . "</a>";
                    exit;
                }

                // Generate prefix table to prevent SQL injection and salt for password

                function generateRandomString($length = 14)
                {
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
                    $charactersLength = strlen($characters);
                    $tableprefix = '';
                    for ($i = 0; $i < $length; $i++) {
                        $tableprefix .= $characters[random_int(0, $charactersLength - 1)];
                    }
                    return $tableprefix;
                }

                $tableprefix = generateRandomString();
                $passwordsalt = generateRandomString();

                //Create file database connection

                $dbfile = '../../functions/data-base.php';

                $contenu = '<?php' . PHP_EOL .
                    '$isactivedb=true;' . PHP_EOL .
                    '$tableprefix="' . $tableprefix . '";' . PHP_EOL .
                    '$passwordsalt="' . $passwordsalt . '";' . PHP_EOL .
                    '$host="' . $host . '";' . PHP_EOL .
                    '$db="' . $db . '";' . PHP_EOL .
                    '$user="' . $user . '";' . PHP_EOL .
                    '$passwd="' . $passwd . '";' . PHP_EOL .
                    PHP_EOL .
                    'if ($isactivedb == false) {' . PHP_EOL .
                    '} else {' . PHP_EOL .
                    'try {' . PHP_EOL .
                    '    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));' . PHP_EOL .
                    '    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);' . PHP_EOL .
                    '} catch(Exception $e) {' . PHP_EOL .
                    '    echo "Erreur : ".$e->getMessage()."<br />";' . PHP_EOL .
                    '}' . PHP_EOL .
                    '}' . PHP_EOL .
                    '?>';

                if (file_put_contents($dbfile, $contenu) !== false) {

                    // Create table
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "CREATE TABLE `{$tableprefix}-collection-users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `c-email` VARCHAR(64) NOT NULL,
      `c-name` VARCHAR(64) NOT NULL,
      `c-role` int(11) NOT NULL,
      `c-devmode` int(11) NOT NULL,
      `c-language` VARCHAR(3) NOT NULL,
      `c-password` VARCHAR(120) NOT NULL,
      `c-token` VARCHAR(120) NOT NULL,
      `c-created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `c-lastmove` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $pdo->exec($sql);

                    // Create table "site_settings"
                    $sql = "CREATE TABLE `{$tableprefix}-collection-settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `c-main-language` VARCHAR(4) NOT NULL,
        `c-all-languages` VARCHAR(120) NOT NULL,
        `c-name` VARCHAR(64) NOT NULL,
        `c-url` VARCHAR(88) NOT NULL,
        `c-cookies` int(11) NOT NULL,
        `c-index` int(11) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $pdo->exec($sql);

                    // Create table for apparence
                    $sql = "CREATE TABLE `{$tableprefix}-collection-apparence` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `c-logo` VARCHAR(88) NOT NULL,
        `c-favicon` VARCHAR(88) NOT NULL,
        `c-color` VARCHAR(28) NOT NULL,
        `c-textcolor` VARCHAR(28) NOT NULL,
        `c-ncolor` VARCHAR(28) NOT NULL,
        `c-palette` VARCHAR(512) NOT NULL,
        `c-corner` int(11) NOT NULL,
        `c-title` VARCHAR(64) NOT NULL, 
        `c-body` VARCHAR(64) NOT NULL,
        `c-structure` int(11) NOT NULL,
        `c-header` int(11) NOT NULL,
        `c-footer` int(11) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $pdo->exec($sql);

                    header("Location: ./create-admin/");
                    die();
                } else {
                    echo $translation['errorfile'];
                }
            }

            if ($translation != null) {
                echo '<h1>' . $translation["title"] . '</h1> <p>' . $translation["description"] . ' </p>';
                echo '<form action="./" method="POST">
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