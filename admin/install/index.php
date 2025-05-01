<!doctype html>
<?php

include("../../functions/data-base.php");

if ($isactivedb == true) {
    header("Location: ./create-admin/");
    die();
}

include("./translation-install.php");

?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../admin-assets/collection.css">
    <link rel="icon" href="../admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["connect-db"] ?></title>
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
                    echo "<a href='./'>" . $translation['retry'] . "</a>";
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

                    // Create table users
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "CREATE TABLE `{$tableprefix}-collection-users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `cemail` VARCHAR(64) NOT NULL,
      `cname` VARCHAR(64) NOT NULL,
      `crole` int(11) NOT NULL,
      `cdevmode` int(11) NOT NULL,
      `clanguage` VARCHAR(3) NOT NULL,
      `cpassword` VARCHAR(120) NOT NULL,
      `ctoken` VARCHAR(120) NOT NULL,
      `ccreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `clastmove` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $pdo->exec($sql);

                    // Create table settings
                    $sql = "CREATE TABLE `{$tableprefix}-collection-settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `cpropriety` VARCHAR(24) NOT NULL,
        `cvalue` VARCHAR(540) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $pdo->exec($sql);

                    // Create table for apparence
                    $sql = "CREATE TABLE `{$tableprefix}-collection-apparence` (
       `id` int(11) NOT NULL AUTO_INCREMENT,
        `cpropriety` VARCHAR(24) NOT NULL,
        `cvalue` VARCHAR(540) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $pdo->exec($sql);

                    // Create table for media
                    $sql = "CREATE TABLE `{$tableprefix}-collection-medias` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                         `caddress` VARCHAR(240) NOT NULL,
                         `csmall` int(11) NOT NULL,
                         `cdate` date NOT NULL,
                         `cextension` VARCHAR(24) NOT NULL,
                         `ctype` VARCHAR(24) NOT NULL,
                         `calt` VARCHAR(240) NOT NULL,
                         `csize` int(11) NOT NULL,
                         `cheight` int(11) NOT NULL,
                         `cwidth` int(11) NOT NULL,
                         `cowner` int(11) NOT NULL,
                         `cattachments` VARCHAR(540) NOT NULL,
                         PRIMARY KEY (`id`)
                     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $pdo->exec($sql);

                    // Create table for pages
                    $sql = "CREATE TABLE `{$tableprefix}-collection-pages` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
         `ctitle` VARCHAR(72) NOT NULL,
         `cdescription` VARCHAR(164) NOT NULL,
         `cslug` VARCHAR(72) NOT NULL,
         `cpath` VARCHAR(240) NOT NULL,
         `chtml` text NOT NULL,
         `ceditor` text NOT NULL,
         `csearchvisibility` int(11) NOT NULL,
         `cvisitoracess` int(11) NOT NULL,
         `cowner` int(11) NOT NULL,
         `cpreview` VARCHAR(240) NOT NULL,
         `clangkey` VARCHAR(24) NOT NULL,
         `clang`  VARCHAR(11) NOT NULL,
         PRIMARY KEY (`id`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $pdo->exec($sql);

                    // Insert Home page and 404 pages

                    $htmlhome = `<h1>{$translation["homepage"]}</h1>`;
                    $html404 = `<h1>{$translation["page404"]}</h1>`;

                    $sql = "INSERT INTO `{$tableprefix}-collection-pages`(`id`, 
                    `ctitle`,
                    `cdescription`,
                    `cslug`, 
                    `cpath`, 
                    `chtml`, 
                    `ceditor`, 
                    `csearchvisibility`, 
                    `cvisitoracess`, 
                    `cowner`, 
                    `cpreview`, 
                    `clangkey`, 
                    `clang`) 
                    VALUES (null,
                    `{$translation["homepage"]}`,
                    '',
                    '1homepage',
                    '1homepage',
                    `{$translation["homepage"]}`,
                    '0',
                    '1',
                    '1',
                    '0',
                    '',
                    'AdbZ13md',
                    `{$translation["lang"]}`";

                    $pdo->exec($sql);

                    $sql = "INSERT INTO `{$tableprefix}-collection-pages`(`id`, 
                    `ctitle`,
                    `cdescription`,
                    `cslug`, 
                    `cpath`, 
                    `chtml`, 
                    `ceditor`, 
                    `csearchvisibility`, 
                    `cvisitoracess`, 
                    `cowner`, 
                    `cpreview`, 
                    `clangkey`, 
                    `clang`) 
                    VALUES ('',
                    `{$translation["page404"]}`,
                    '',
                    '404page',
                    '404page',
                    `{$translation["page404"]}`,
                    '0',
                    '1',
                    '1',
                    '0',
                    '',
                    '22ADFqhB',
                    `{$translation["lang"]}`";

                    $pdo->exec($sql);


                    header("Location: ./create-admin/");
                    die();
                } else {
                    echo $translation['errorfile'];
                }
            }

            if ($translation != null) {
                echo '<h1>' . $translation["connect-db"] . '</h1> <p>' . $translation["description-db"] . ' </p>';
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