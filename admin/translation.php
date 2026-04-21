<?php
$lang = $_SESSION["lang"];
$translations = array(
    array(
        "lang" => "fr",
        "title" => "Modifier le site",
        "page" => "Pages",
        "media" => "Médias",
        "announcement" => "Annonces",
        "setting" => "Paramètres",
        "component" => "Composants",
        "form" => "Formulaires",
        "newsletter" => "Infolettre",
        "managesub" => "Gérer les abonnés",
        "topinfo" => "Barre d'information",
        "apparence" => "Apparence",
        "account" => "Comptes",
        "scriptcompliance" => "Script et conformité Rgpd",
        "webperformance" => "Performance web",
        "seo" => "Référencement",
        "close" => "Fermer",
        "date" => "Date",
        "weight" => "Poids",
        "file" => "fichier",
        "internalsearchengine" => "Moteur de recherche interne",
        "colors" => "Couleurs",
        "favicon" => "Icon d'apparence",
        "favicondescription" => "Cette icon s'affichera dans les onglets et écran d'accueil des navigateur.",
        "address" => "Adresse",
        "alternativetext" => "Alternative textuelle",
        "notwebvideo" => "Format video non web",
        "formatvideo" => "Ce format vidéo n'est pas optimisé pour la lecture des vidéos en ligne. Convertissez la en <b>Webm</b> ou <b>mp4</b> avec <a href='https://handbrake.fr/downloads.php' target='_blank'> Handbrake </a>.",
        "editfavicon" => "Modifier l’icône",
        "uploading" => "Téléversement en cours",
        "addfile" => "Ajouter un fichier",
        "namedescription" => "Cela vous aidera à le retrouver et c'est nécessaire pour les personnes mal voyante.",
        "yourfile" => "Votre fichier",
        "describeyourfile" => "Décrivez votre fichier",
        "close" => "Fermer",
        "modaletitle" => "Mise en ligne",
        "upload" => "Mettre en ligne",
        "maxsize" => "Taille maximum de mise en ligne :",

        
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