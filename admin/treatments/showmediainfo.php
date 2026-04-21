<?php
$access ="../";
include($access."../functions/data-base.php");
session_start();

include($access."translation.php");


$mediaId = $_POST['mediaId'];

if (is_numeric($mediaId)) {
    try {

        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM `{$tableprefix}-collection-medias` WHERE `id` =  $mediaId";
        $result = $pdo->query($sql);

        $medias = $result->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($medias)) {
            $media = $medias[0];

            if ($media["ctype"] == "image") {
                echo "
                        <div class='modale-galery'>
                            <div class='modale-visual'>
                                <img src=\"../../assets/" . $media["caddress"] . "\" alt=\"" . $media["calt"] . "\"/>
                            </div>
                            <div class='mediaspec'>
                                <h2>" . $media["calt"] . "</h2>
                                <ul>
                                    <li><img class='isdark' src='../admin-assets/address-d.svg' alt=" . $translation['address'] . "><img class='islight' src='../admin-assets/address-l.svg' alt=". $translation['address'] . "/>" . $media["caddress"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/date-d.svg' alt=" . $translation['date'] . "><img class='islight' src='../admin-assets/date-l.svg' alt=". $translation['date'] . "/>" . $media["cdate"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/file-d.svg' alt=" . $translation['file'] . "><img class='islight' src='../admin-assets/file-l.svg' alt=". $translation['file'] . "/>" . $media["ctype"] . " (" .  $media["cextension"] . ")</li>
                                    <li><img class='isdark' src='../admin-assets/alt-d.svg' alt=" . $translation['alternativetext'] . "><img class='islight' src='../admin-assets/alt-l.svg' alt=". $translation['alternativetext'] . "/>" . $media["calt"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/weight-d.svg' alt=" . $translation['weight'] . "><img class='islight' src='../admin-assets/weight-l.svg' alt=". $translation['weight'] . "/>" . $media["csize"] . " Ko</li>
                                    <li><strong>Hauteur</strong> : " . $media["cheight"] . " pixels</li>
                                    <li><strong>Largeur</strong> : " . $media["cwidth"] . " pixels</li>
                                    <li><strong>Propriétaire</strong> : " . $media["cowner"] . "</li>
                                </ul>
                                <button class=\"destructive\" onclick=\"deletefile(" . $media["id"] . ", '" . $media["caddress"] . "')\">Supprimer</button>
                            </div>
                        </div>
                    ";
            } elseif ($media["ctype"] == "video") {
                echo "
                        <div class='modale-galery'>
                            <div class='modale-visual'>
                                <video controls>
                                   <source src='../../assets/" . $media["caddress"] . "' type='video/". $media["cextension"] ."' />
                                </video>
                            </div>
                            <div class='mediaspec'>
                                <h2>" . $media["calt"] . "</h2>
                                <ul>
                                    <li><img class='isdark' src='../admin-assets/address-d.svg' alt=" . $translation['address'] . "><img class='islight' src='../admin-assets/address-l.svg' alt=". $translation['address'] . "/>" . $media["caddress"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/date-d.svg' alt=" . $translation['date'] . "><img class='islight' src='../admin-assets/date-l.svg' alt=". $translation['date'] . "/>" . $media["cdate"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/file-d.svg' alt=" . $translation['file'] . "><img class='islight' src='../admin-assets/file-l.svg' alt=". $translation['file'] . "/>" . $media["ctype"] . " (" .  $media["cextension"] . ") ";
                                    if ($media["cextension"] !== "mp4" && $media["cextension"] !== "webm") {
                                        echo "<br><div class='tag' style='background-color: #eaaeae; color: #602020; position: relative;'> " . $translation['formatvideo'] . "</div>";
                                    } 
                                    echo "
                                    </li>
                                    <li><img class='isdark' src='../admin-assets/alt-d.svg' alt=" . $translation['alternativetext'] . "><img class='islight' src='../admin-assets/alt-l.svg' alt=". $translation['alternativetext'] . "/>" . $media["calt"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/weight-d.svg' alt=" . $translation['weight'] . "><img class='islight' src='../admin-assets/weight-l.svg' alt=". $translation['weight'] . "/>" . $media["csize"] . " Ko</li>
                                    <li><strong>Propriétaire</strong> : " . $media["cowner"] . "</li>
                                </ul>
                                <button class=\"destructive\" onclick=\"deletefile(" . $media["id"] . ", '" . $media["caddress"] . "')\">Supprimer</button>
                            </div>
                        </div>
                    ";
            }  elseif ($media["cextension"] == "pdf") {
                echo "
                        <div class='modale-galery'>
                            <div class='modale-visual'>
                                   <iframe src='../../assets/" . $media["caddress"] . "'></iframe>
                            </div>
                            <div class='mediaspec'>
                                <h2>" . $media["calt"] . "</h2>
                                <ul>
                                    <li><img class='isdark' src='../admin-assets/address-d.svg' alt=" . $translation['address'] . "><img class='islight' src='../admin-assets/address-l.svg' alt=". $translation['address'] . "/>" . $media["caddress"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/date-d.svg' alt=" . $translation['date'] . "><img class='islight' src='../admin-assets/date-l.svg' alt=". $translation['date'] . "/>" . $media["cdate"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/file-d.svg' alt=" . $translation['file'] . "><img class='islight' src='../admin-assets/file-l.svg' alt=". $translation['file'] . "/>" . $media["ctype"] . " (" .  $media["cextension"] . ")</li>
                                    <li><img class='isdark' src='../admin-assets/alt-d.svg' alt=" . $translation['alternativetext'] . "><img class='islight' src='../admin-assets/alt-l.svg' alt=". $translation['alternativetext'] . "/>" . $media["calt"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/weight-d.svg' alt=" . $translation['weight'] . "><img class='islight' src='../admin-assets/weight-l.svg' alt=". $translation['weight'] . "/>" . $media["csize"] . " Ko</li>
                                    <li><strong>Propriétaire</strong> : " . $media["cowner"] . "</li>
                                </ul>
                                <button class=\"destructive\" onclick=\"deletefile(" . $media["id"] . ", '" . $media["caddress"] . "')\">Supprimer</button>
                            </div>
                        </div>
                    ";
            } else {
                echo "
                        <div class='modale-galery'>
                            <div class='modale-visual'>
                                    
                            </div>
                            <div class='mediaspec'>
                                <h2>" . $media["calt"] . "</h2>
                                <ul>
                                    <li><img class='isdark' src='../admin-assets/address-d.svg' alt=" . $translation['address'] . "><img class='islight' src='../admin-assets/address-l.svg' alt=". $translation['address'] . "/>" . $media["caddress"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/date-d.svg' alt=" . $translation['date'] . "><img class='islight' src='../admin-assets/date-l.svg' alt=". $translation['date'] . "/>" . $media["cdate"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/file-d.svg' alt=" . $translation['file'] . "><img class='islight' src='../admin-assets/file-l.svg' alt=". $translation['file'] . "/>" . $media["ctype"] . " (" .  $media["cextension"] . ")</li>
                                    <li><img class='isdark' src='../admin-assets/alt-d.svg' alt=" . $translation['alternativetext'] . "><img class='islight' src='../admin-assets/alt-l.svg' alt=". $translation['alternativetext'] . "/>" . $media["calt"] . "</li>
                                    <li><img class='isdark' src='../admin-assets/weight-d.svg' alt=" . $translation['weight'] . "><img class='islight' src='../admin-assets/weight-l.svg' alt=". $translation['weight'] . "/>" . $media["csize"] . " Ko</li>
                                    <li><strong>Propriétaire</strong> : " . $media["cowner"] . "</li>
                                </ul>
                                <button class=\"destructive\" onclick=\"deletefile(" . $media["id"] . ", '" . $media["caddress"] . "')\">Supprimer</button>
                            </div>
                        </div>
                    ";
            }

        } else {
            echo "Aucun média trouvé avec l'ID $mediaId.";
        }
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    exit;
}
