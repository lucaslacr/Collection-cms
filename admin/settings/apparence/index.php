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
    <title><?php echo $translation["apparence"] ?></title>
    <style>
        .casvide {
            height: 64px;
            width: 64px;
            border: 1px dashed var(--line-accessible);
            border-radius: 4px;
        }
        .setfavicon {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 0;
            font-size: 14px;
            color: var(--text-title);
        }
    </style>
</head>

<body>
    <div class="adminpage">
        <?php

        include($access . "../functions/comp-navadmin.php");

        ?>
        <div class="admincontent">
            <main>
                <h1><?= $translation["apparence"] ?></h1>

                <h2><?= $translation["colors"] ?></h2>

                <h2><?= $translation["favicon"] ?></h2>
                <p><?= $translation["favicondescription"] ?></p>

                <?php

                $sql = "SELECT * FROM `{$tableprefix}-collection-apparence` WHERE `cpropriety` = 'favicon'";
                $result = $pdo->query($sql);

                if ($result->rowCount() > 0) {
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    echo $row['cvalue'];
                } else {
                    echo "<button class='nostyle setfavicon' aria-haspopup='dialog' onclick='dialog.showModal()'>
                    <div class='casvide'></div>
                    <div>" .  $translation["editfavicon"] . "</div>
                </button>";
                }

                ?>
               <dialog id="dialog">
                        <h4><?= $translation['modaletitle'] ?></h4>
                        <form method="dialog">
                            <button><img class="isdark" src="<?= $access ?>admin-assets/close-d.svg" alt="<?= $translation['close'] ?>"><img class="islight" src="<?= $access ?>admin-assets/close-l.svg" alt="<?= $translation['close'] ?>"></button>
                        </form>
                        <p>Nous vous recommandons de choisir une image de 192 x 192 px en Png</p>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <label for="file"><?= $translation['yourfile'] ?></label>
                            <input type="file" id="file" name="file" accept="image/png, image/jpeg" required />
                            <input type="submit" id="submit" value="<?= $translation['upload'] ?>" />
                            <div id="load" class="loadingbouton displaynone" role="status">
                                <div class="isloading" aria-hidden="true">
                                    <div></div>
                                </div>
                                <?= $translation['uploading'] ?>
                            </div>
                            <p id="responseMessage"></p>
                        </form>
                    </dialog>


            </main>

        </div>
    </div>
</body>

</html>