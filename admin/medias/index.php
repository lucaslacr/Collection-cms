<?php
 $access ="../";
include($access."../functions/data-base.php");
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

include($access . "translation.php");

$lang = $_SESSION["lang"];
echo '<!DOCTYPE html><html lang="' . $lang . '"';
?>

<!DOCTYPE html>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../admin-assets/collection.css">
    <link rel="icon" href="../admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["media"] ?></title>
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
            width: 100%;
            position: relative;
        }

        .tag {
            padding: 3px 7px;
            border-radius: 4px;
            position: absolute;
            right: 6px;
            top: 8px;
            color: #604620;
            background-color: #e6ccb3;
        }

        .listmedia li {
            max-width: 280px;
            width: 100%;
        }

        .listmedia li p {
            font-size: 14px;
            text-align: left;
            margin-top: 4px;
            margin-left: 2px;
        }

        .modale-galery {
            display: flex;
            flex-direction: row;
            gap: 24px;
        }

        @media (max-width: 860px) {
            .modale-galery {
                flex-direction: column;
            }
        }

        .modale-visual img {
            max-width: 720px;
            width: 100%;
        }

        .modale-visual {
            flex: 2;
            min-width: 280px;
        }

        .mediaspec {
            flex: 1;
            min-width: 280px;
        }

        .mediaspec img {
            height: 24px;
            width: 24px;
            margin: 0;
        }

        .mediaspec ul {
            list-style: none;
            color: var(--text-paragraph);
            margin: 0;
        }

        .mediaspec ul li {
            display: flex;
            gap: 6px;
        }

        #mediainfo h2 {
            margin-top: 40px;
            font-size: 24px;
        }

        .skeleton {
            animation: colorload 1.4s linear infinite alternate;
        }

        @keyframes colorload {
            0% {
                background-color:var(--surface-skeleton-low)
            }

            to {
                background-color:var(--surface-skeleton-hight)
            }
        }

        button.destructive {
            color: var(--text-alert);
            border: var(--text-alert) 1px solid;
            background-color: #0000;
        }

        .previewbg {
            width: 100%;
            aspect-ratio: 4.5/3;
            background-position: center;
            background-size: cover;
            border-radius: 8px;
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
                    <h1><?= $translation['media'] ?></h1>
                    <dialog id="dialog">
                        <h4><?= $translation['modaletitle'] ?></h4>
                        <form method="dialog">
                            <button><img class="isdark" src="../admin-assets/close-d.svg" alt="<?= $translation['close'] ?>"><img class="islight" src="../admin-assets/close-l.svg" alt="<?= $translation['close'] ?>"></button>
                        </form>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <label for="file"><?= $translation['yourfile'] ?></label>
                            <p><?=$translation['maxsize'] . " " . ini_get("upload_max_filesize");?></p>
                            <input type="file" id="file" name="file" required />
                            <label for="filename"><?= $translation['describeyourfile'] ?></label>
                            <p><?= $translation['namedescription'] ?></p>
                            <input type="text" name="filename" id="filename" minlength="4" maxlength="60" required />
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

                        echo "<li id='elem" . htmlspecialchars($row["id"]) . "'>
                        <button aria-haspopup='dialog' data-media='" . htmlspecialchars($row["id"]) . "' onclick='showMediaInfo(this)'>
                        ";

                        if ($row["ctype"] == "image") {
                            echo "<div class='previewbg' style='background-image: url(../../assets/" . $low . $row["caddress"] . ")'></div>";
                        } else {
                        }
                        echo "<p>" . htmlspecialchars($row["calt"]) . "<p>";
                        if ($row["csize"] > "290") {
                            if ($row["csize"] > "1000") {
                                $number = $row["csize"] / 1000;
                                echo "<div class='tag' style='background-color: #eaaeae; color: #602020;'> " . round($number, 1) . " Mo</div>";
                            } else {
                                echo "<div class='tag'> " . htmlspecialchars($row["csize"]) . " Ko</div>";
                            }
                        }
                        if ($row["ctype"] == "video") {
                            if ($row["cextension"] !== "mp4" && $row["cextension"] !== "webm") {
                                echo "<div class='tag' style='background-color: #eaaeae; color: #602020;'> No web format</div>";
                            }
                        }
                        echo " </button>
                        </li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                } else {
                    echo "Aucun fichier téléchargé pour le moment.";
                }
                ?>
                <div id="modalemediaarea"></div>
            </main>
        </div>
    </div>
    <script>
        // display info
        function showMediaInfo(element) {
            const mediaId = element.getAttribute('data-media');
            const formData = new FormData();
            formData.append('mediaId', mediaId);

            let modalemedia = document.getElementById("modalemediaarea");
            modalemedia.innerHTML = `<dialog id="mediainfo" aria-busy="true">
            <div id="infolayout"> 
                <div class="modale-galery">
                    <div class="modale-visual">
                    <div class="skeleton" style="width: 100%; height:100%; min-height: 280px; border-radius: 8px;"></div>
                </div>
                <div class="mediaspec"></div>
            </div></div>
            <form method="dialog">
            <button><img class="isdark" src="../admin-assets/close-d.svg" alt="<?= $translation['close'] ?>"><img class="islight" src="../admin-assets/close-l.svg" alt="<?= $translation['close'] ?>"></button>
            </form>
            </dialog>`;

            document.getElementById("mediainfo").showModal();

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
                        document.getElementById("infolayout").innerHTML = data;
                        document.getElementById("mediainfo").setAttribute('aria-busy', 'false');
                    } catch (error) {
                        console.error('Erreur de parsing JSON:', error);
                        console.log('Réponse du serveur:', data);
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
    </script>
    <script>
        // send file
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();

            document.getElementById("submit").classList.add("displaynone");
            document.getElementById("load").classList.remove("displaynone");

            const formData = new FormData(this);

            fetch('../treatments/uploadfile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de l\'upload du fichier');
                    }
                    return response.text();
                })
                .then(data => {
                //    location.reload();
                alert(data);
                })
                .catch(error => {
                    document.getElementById('responseMessage').innerHTML = "<div role ='dialog'>" + error.message + "</div>";
                });
        });
    </script>
    <script>
        // delete a file
        function deletefile(fileId, slug) {
            const data = {
                id: fileId,
                slug: slug
            };

            const filelist = document.getElementById("elem" + fileId);

            fetch('../treatments/deletefile.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la suppression du fichier');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fichier supprimé avec succès:', data);
                    filelist.remove();
                    document.getElementById('info').close();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        }
    </script>
</body>

</html>