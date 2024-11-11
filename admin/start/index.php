<!doctype html>
<html lang="en">

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

            if (isset($_POST["databasename"])) {

                $host = $_POST["databasehost"];

                $db = $_POST["databasename"];

                $user = $_POST["databaseuser"];

                $passwd = $_POST["databasepassword"];

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (Exception $e) {
                    echo "<h1> La connection à échouée </h1>";
                    echo "<p>";
                    echo "Erreur : " . $e->getMessage() . "<br />";
                    echo "</p>";
                    echo "<a href='../'>Réessayer avec d'autres identifiants</a>";
                    exit;
                }
                echo "<h1> La connection est établie</h1>";

                $dbfile = '../../functions/data-base.php';

                $contenu = '<?php' . PHP_EOL .
                    '$isactivedb=true;' . PHP_EOL .
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
                    echo "Le fichier a été créé avec succès.";
                } else {
                    echo "Une erreur est survenue lors de la création du fichier.";
                }
            }
            ?>
        </section>
    </main>
</body>

</html>