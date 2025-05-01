<?php
 $access ="../../";
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

include($access."translation.php");

$lang = $_SESSION["lang"];
echo '<html lang="' . $lang . '"';

?>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="<?= $access ?>admin-assets/collection.css">
    <link rel="icon" href="<?= $access ?>admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["webperformance"] ?></title>
</head>

<body>
    <div class="adminpage">
    <?php
      
       include($access . "../functions/comp-navadmin.php");
       
       ?>
        <div class="admincontent">
            <main>
            <h1><?=  $translation["webperformance"] ?></h1>
                
            <form>
            <input type="checkbox" role="switch" id="active" checked="">
            <label for="active"> Convert new image on Avif </label>       
            </form>

            </main>
            <footer>
                Collection 0.4
            </footer>

        </div>
    </div>
</body>

</html>