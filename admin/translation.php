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
        "internalsearchengine" => "Moteur de recherche interne"
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