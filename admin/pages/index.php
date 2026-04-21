<?php
include("../../functions/data-base.php");
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

$lang = $_SESSION["lang"];
echo '<!DOCTYPE html><html lang="' . $lang . '"';

$translations = array(
    array(
        "lang" => "fr",
        "title" => "Pages du site",
        "h1" => "Pages",
        "addpage" => "Ajouter une page",
        "namedescription" => "Cela sera utile pour retrouver votre fichier",
        "titlepage" => "Titre de votre page",
        "titledescription" => "60 caractères maximum",
        "close" => "Fermer",
        "modaletitle" => "Créer une nouvelle page",
        "createpage" => "Créer la page",
        "current" => "Fréquent",
        "others" => "Autres",
        "root" => "À la racine",
        "path" => "Page parente",
        "edit" => "Modifier la page",
        "view" => "Voir"
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

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file'];
    $filename = $_POST['filename'];

    // Slug generation
    $slug = strtolower(str_replace(' ', '-', preg_replace('/[\'"]/', '', $filename)));
    $slug = strtr($slug, 'àáâãäåèéêëìíîïòóôõöùúûüÿ', 'aaaaaaeeeeiiiiooooouuuuy');
    $slug = preg_replace('/[A-Z]/', '', $slug);

    $file_alt = $filename;
    $file_owner = $_SESSION["id"];

    $sql = "INSERT INTO `{$tableprefix}-collection-medias` (`id`, `caddress`, `csmall`, `cdate`, `cextension`, `ctype`, `calt`, `csize`, `cheight`, `cwidth`, `cowner`)
    VALUES (:id, :caddress, NOW(), :exten, :ctype, :alt, :csize, :fh, :fw, :conwer)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', 0, PDO::PARAM_INT);
    $stmt->bindValue(':caddress', $fileaddress, PDO::PARAM_STR);
    $stmt->bindValue(':exten', $file_extension, PDO::PARAM_STR);
    $stmt->bindValue(':ctype', $file_type, PDO::PARAM_STR);
    $stmt->bindValue(':alt', $filename, PDO::PARAM_STR);
    $stmt->bindValue(':csize', $file_size, PDO::PARAM_STR);
    $stmt->bindValue(':fh', $file_height, PDO::PARAM_STR);
    $stmt->bindValue(':fw', $file_width, PDO::PARAM_STR);
    $stmt->bindValue(':conwer', $_SESSION["id"], PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Insertion réussie
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Erreur lors de l'insertion dans la table `{$tableprefix}-collection-apparence` : " . $errorInfo[2];
    }

    if (file_exists($target_file)) {
        echo "Le fichier a été téléchargé avec succès.";
    } else {
        echo "Une erreur est survenue lors du téléchargement du fichier.";
    }
}
?>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../admin-assets/collection.css">
    <link rel="icon" href="../admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["title"] ?></title>
    <style>
        h1 {
            font-size: 32px;
        }

        #headlist {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            padding-top: 13px;
            padding-bottom: 24px;
        }

        #headlist button {
            margin: 0;
        }

        .listmedia ul {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 12px;
        }

        .listmedia li {
            max-width: 280px;
        }

        ul.listpage li {
            list-style: none;
            padding: 8px 4px;
            border-bottom: 1px solid var(--line-decorative);
            width: 100%;
            max-width: 1200px;
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: space-between;
        }

        ul.listpage li .path {
            font-size: 13px;
            line-height: 100%;
        }

        ul.listpage li .title {
            color: var(--text-title);
        }

        ul.listpage img {
            height: 24px;
            margin: 0;
        }

        ul.listpage a {
            border-bottom: none;
        }

        .action {
            display: flex;
            gap: 8px;
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
        $access = "../";
        include("../../functions/comp-navadmin.php");
        ?>
        <div class="admincontent">
            <main>
                <div id="headlist">
                    <h1><?= $translation['h1'] ?></h1>
                    <dialog id="dialog">
                        <h4><?= $translation['modaletitle'] ?></h4>
                        <form method="dialog">
                            <button><?= $translation['close'] ?></button>
                        </form>
                        <form action="../treatments/createpage.php" method="post" enctype="multipart/form-data">
                            <label for="titlepage"><?= $translation['titlepage'] ?></label>
                            <p> <?= $translation['titledescription'] ?></p>
                            <input type="text" id="titlepage" name="titlepage" minlength="6" maxlength="60" required />

                            <label for="path"><?= $translation['path'] ?></label>
                            <select id="path" name="path">
                                <optgroup label="<?= $translation['current'] ?>">
                                    <option value=""><?= $translation['root'] ?></option>
                                </optgroup>
                                <optgroup label="<?= $translation['other'] ?>">
                                    <option>Ok</option>
                                </optgroup>
                            </select>
                            <input type="submit" value="<?= $translation['createpage'] ?>" />
                        </form>
                    </dialog>
                    <button aria-haspopup="dialog" onclick="dialog.showModal()"><?= $translation['addpage'] ?></button>
                </div>
                <?php

                $sql = "SELECT * FROM `{$tableprefix}-collection-pages`";
                $result = $pdo->query($sql);

                if ($result->rowCount() > 0) {
                    echo "<div class='listmedia'>";
                    echo "<ul class='listpage'>";
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<li>";

                        echo "<div><div class='title'>" . htmlspecialchars($row["ctitle"]) . "</div><div class='path'>" . htmlspecialchars($row["cpath"]) . htmlspecialchars($row["cslug"]) . "</div></div> <div class='action'> <a href='./edit?p=" . htmlspecialchars($row["id"]) . "' aria-label='" .  $translation['edit']  . "'><img class='isdark' src='../admin-assets/edit-d.svg' alt/><img class='islight' src='../admin-assets/edit-l.svg' alt/> </a><a href='../../" . htmlspecialchars($row["cpath"]) . htmlspecialchars($row["cslug"]) . "' target='_blank' aria-label='" .  $translation['view']  . "'><img class='isdark' src='../admin-assets/open-d.svg' alt/><img class='islight' src='../admin-assets/open-l.svg' alt/></a> </div>";

                        echo "</a></li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                } else {
                    echo "Aucun fichier téléchargé pour le moment.";
                }
                ?>
            </main>
        </div>
    </div>
</body>

</html>