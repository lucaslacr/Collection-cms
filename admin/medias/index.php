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
echo '<html lang="' . $lang . '"';

$translations = array(
    array(
        "lang" => "fr",
        "title" => "Médias du site",
        "h1" => "Médias",
        "addfile" => "Ajouter un fichier",
        "namedescription" => "Cela sera utile pour retrouver votre fichier",
        "yourfile" => "Votre fichier",
        "nameyourfile" => "Renommer votre fichier",
        "close" => "Fermer",
        "modaletitle" => "Mise en ligne",
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

<!DOCTYPE html>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../admin-assets/collection.css">
    <link rel="icon" href="../admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["title"] ?></title>
    <style>
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
            list-style: none;
            gap: 12px;
        }

        .listmedia ul button {
            padding: 2px;
            background-color: #0000;
        }

        .listmedia li {
            max-width: 280px;
        }

        .modale-galery {
            display: flex;
            flex-direction: row;
            gap: 24px;
        }

        .modale-visual img {
            max-width: 720px;
            width: 100%;
        }

        button.destructive {
            color: var(--text-alert);
            border: var(--text-alert) 1px solid;
            background-color: #0000;
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
                        <form id="uploadForm" enctype="multipart/form-data">
                            <label for="file"><?= $translation['yourfile'] ?></label>
                            <input type="file" id="file" name="file" required />
                            <label for="filename"><?= $translation['nameyourfile'] ?></label>
                            <p><?= $translation['namedescription'] ?></p>
                            <input type="text" name="filename" id="filename" minlength="4" maxlength="60" required />
                            <input type="submit" value="<?= $translation['upload'] ?>" />
                            <p id="responseMessage"></p>
                        </form>
                    </dialog>
                    <button aria-haspopup="dialog" onclick="dialog.showModal()"><?= $translation['addfile'] ?></button>
                </div>
                <?php
                $sql = "SELECT * FROM `{$tableprefix}-collection-medias` ORDER BY cdate DESC";
                $result = $pdo->query($sql);

                if ($result->rowCount() > 0) {
                    echo "<div class='listmedia'>";
                    echo "<ul>";
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                        if ($row["csmall"] != 1) {
                            $low = "";
                        } else {
                            $low = "s/";
                        }

                        echo "<li id='elem". htmlspecialchars($row["id"]) ."'>
                        <button aria-haspopup='dialog' data-media='" . htmlspecialchars($row["id"]) . "' onclick='showMediaInfo(this)'>
                        ";

                        if ($row["ctype"] == "image") {
                            echo "<img src='../../assets/" . $low . $row["caddress"] . "' alt >";
                        } else {
                        }
                        echo "Texte alternatif : " . htmlspecialchars($row["calt"]) . "<br>";
                        echo "Taille : " . htmlspecialchars($row["csize"]) . " Ko<br>";
                        echo " </button>
                        </li>";
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
    <dialog id="mediaDialog">
        <h2>Détails de l'image</h2>
        <div id="mediaContent"></div>
        <button onclick="closeDialog()">Fermer</button>
    </dialog>
    <script>
        function showMediaInfo(element) {
            const mediaId = element.getAttribute('data-media');
            const formData = new FormData();
            formData.append('mediaId', mediaId);

            fetch('../treatments/showmediainfo.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP ! statut : ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    try {
                        const jsonData = JSON.parse(data);
                        const dialog = document.createElement('dialog');
                        dialog.id = "info"; 
                        dialog.innerHTML = `
     
       <div class='modale-galery'>
       <div class='modale-visual'>
            <img src="../../assets/${jsonData[0].caddress}" alt="${jsonData[0].calt}">
       </div>
       <div>
        <h2>${jsonData[0].calt}</h2>
        <ul>
          <li><strong>ID</strong> : ${jsonData[0].id}</li>
          <li><strong>Adresse</strong> : ${jsonData[0].caddress}</li>
          <li><strong>Miniature</strong> : ${jsonData[0].csmall === 1 ? 'Oui' : 'Non'}</li>
          <li><strong>Date</strong> : ${jsonData[0].cdate}</li>
          <li><strong>Extension</strong> : ${jsonData[0].cextension}</li>
          <li><strong>Type</strong> : ${jsonData[0].ctype}</li>
          <li><strong>Texte alternatif</strong> : ${jsonData[0].calt}</li>
          <li><strong>Taille</strong> : ${jsonData[0].csize} Ko</li>
          <li><strong>Hauteur</strong> : ${jsonData[0].cheight} pixels</li>
          <li><strong>Largeur</strong> : ${jsonData[0].cwidth} pixels</li>
          <li><strong>Propriétaire</strong> : ${jsonData[0].cowner}</li>
          <li><strong>Pièces jointes</strong> : ${jsonData[0].cattachments === null || jsonData[0].cattachments === '' ? 'Aucune' : jsonData[0].cattachments}</li>
        </ul>
        <button onclick="this.closest('dialog').close()">Fermer</button>
        <button class="destructive" onclick="deletefile(${jsonData[0].id}, '${jsonData[0].caddress}')">Supprimer</button>
        </div>
        </div>
      `;
                        document.body.appendChild(dialog);
                        dialog.showModal();
                    } catch (error) {
                        console.error('Erreur de parsing JSON:', error);
                        console.log('Réponse du serveur:', data);
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
    </script>
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page

            const formData = new FormData(this); // Crée un objet FormData à partir du formulaire

            fetch('../treatments/uploadfile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de l\'upload du fichier');
                    }
                    return response.text(); // ou response.json() si votre PHP renvoie du JSON
                })
                .then(data => {
                    // Affichez un message de succès ou traitez la réponse ici
                    location.reload();
                })
                .catch(error => {
                    // Gérer les erreurs
                    document.getElementById('responseMessage').innerHTML = "<div role ='dialog'>" + error.message + "</div>";
                });
        });
    </script>
    <script>
        function deletefile(fileId, slug) {
            // Créer l'objet de données à envoyer
            const data = {
                id: fileId,
                slug: slug
            };

            const filelist = document.getElementById("elem" + fileId);

            // Envoyer la requête POST
            fetch('../treatments/deletefile.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Indique que nous envoyons des données JSON
                    },
                    body: JSON.stringify(data), // Convertir l'objet en chaîne JSON
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la suppression du fichier');
                    }
                    return response.json(); // Convertir la réponse en JSON
                })
                .then(data => {
                    // Traiter la réponse du serveur
                    console.log('Fichier supprimé avec succès:', data);
                    filelist.remove();
                    document.getElementById('info').close();
                    // Vous pouvez également mettre à jour l'interface utilisateur ici
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        }
    </script>
</body>

</html>