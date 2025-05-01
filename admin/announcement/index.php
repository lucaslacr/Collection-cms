<?php
include("../../functions/data-base.php");
session_start();

if ($isactivedb != true) {
    header("Location: ../install");
    die();
}

if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
} else {
    header("Location: ../login");
    die();
}
include("../translation.php");

$sql = "SELECT * FROM `{$tableprefix}-collection-settings` WHERE `cpropriety` = 'sitename'";
$result = $pdo->query($sql);

if ($result->rowCount() > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if (!empty($row['cvalue'])) {
    } else {
        header("Location: ../install/start/");
        die();
    }
} else {
    header("Location: ../install/start/");
    die();
}

$lang = $_SESSION["lang"];
echo '<html lang="' . $lang . '"';
?>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../admin-assets/collection.css">
    <link rel="icon" href="../admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["title"] ?></title>
</head>

<body>
    <div class="adminpage">
        <?php
        $access = "../";
        include("../../functions/comp-navadmin.php");

        ?>
        <div class="admincontent">
            <main>
                <h1><?=  $translation["announcement"] ?></h1>
                <ul class="page-list"> 
                    <li><a href="./topinfo"><?=  $translation["topinfo"] ?></a></li>
                    <li><a href="./topinfo"><?=  $translation["newsletter"] ?></a></li>
                    <li><a href="./topinfo"><?=  $translation["managesub"] ?></a></li>
                </ul>
                
            </main>
            <footer>
                Collection 0.4
            </footer>

        </div>
    </div>
</body>

</html>