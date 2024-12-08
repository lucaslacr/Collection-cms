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
    header("Location: ../install/start/");
    die();
}

$lang = $_SESSION["lang"];
echo '<html lang="' . $lang . '"';

$translations = array(
    array(
        "lang" => "fr",
        "title" => "Médias du site",
        "h1" => "Médias",
        "addfile" => "Ajouter un fichier",
        "namedescription" => "Cela sera utile pour retrouver votre fichier",
        "close" => "Fermer",
        "upload" => "Mettre en ligne",
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

      // Créer un slug à partir du nom du fichier
      $slug = strtolower(str_replace(' ', '-', $filename));

      // Définir le chemin du fichier
      $target_dir = '../../assets/';
      $target_file = $target_dir . basename($slug . '.webp');
  
      // Vérifier si le fichier est une image JPG ou PNG
      $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
      if ($file_extension === 'jpg' || $file_extension === 'png' || $file_extension === 'jpeg') {

        if ($file_extension === 'png') {
            $image_size = getimagesize($file['tmp_name']);
            if ($image_size[0] === $image_size[1] && $image_size[1] < 201) {
                // Déplacer le fichier PNG vers le dossier cible sans conversion
                $target_file = $target_dir . basename($slug . '.png');
                move_uploaded_file($file['tmp_name'], $target_file);
                echo "Le fichier PNG carré et plus petit que 200 pixels de hauteur a été téléchargé sans conversion.";
                exit;
            }
        }

          // Convertir l'image en WebP avec une compression de 82%
          $image = imagecreatefromstring(file_get_contents($file['tmp_name']));
          imagewebp($image, $target_file, 82);
          imagedestroy($image);
      } else {
          $target_file = $target_dir . basename($slug . '.' . $file_extension);
          move_uploaded_file($file['tmp_name'], $target_file);
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
        $access = "../";
        include("../../functions/comp-navadmin.php");

        ?>
        <div class="admincontent">
            <main>
                <h1><?= $translation['h1'] ?></h1>
                <dialog id="dialog">
                    <h4>I'm a modal</h4>
                    <form method="dialog">
                        <button><?= $translation['close'] ?></button>
                    </form>
                    <form action="" method="post" enctype="multipart/form-data">
                        <label for="file"><?= $translation['yourfile'] ?></label>
                        <input type="file" id="file" name="file" required />

                        <label for="filename"><?= $translation['nameyour'] ?></label>
                        <p><?= $translation['namedescription'] ?></p>
                        <input type="text" name="filename" id="filename" minlength="4" maxlength="60" required />

                        <input type="submit" value="<?= $translation['upload'] ?>" />
                    </form>
                    </form>
                </dialog>
                <button aria-haspopup="dialog" onclick="dialog.showModal()"><?= $translation['addfile'] ?></button>
                <p><?= $row["c-site-name"]; ?>
            </main>
            <footer>
                Collection 0.4
            </footer>
        </div>
    </div>
</body>

</html>