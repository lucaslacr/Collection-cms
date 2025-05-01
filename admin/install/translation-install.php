<?php

if (!isset($lang)) {
    $htmllang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

    switch ($htmllang) {
        case "fr":
            echo '<html lang="fr"';
            $lang = "fr";
            break;
        case "en":
            echo '<html lang="en"';
            $lang = "en";
            break;
        case "es":
            echo '<html lang="es"';
            $lang = "es";
            break;
        case "it":
            echo '<html lang="it"';
            $lang = "it";
            break;
        case "de":
            echo '<html lang="de"';
            $lang = "de";
            break;
        case "nl":
            echo '<html lang="nl"';
            $lang = "nl";
            break;
        case "pt":
            echo '<html lang="pt"';
            $lang = "pt";
            break;
        default:
            echo '<html lang="en"';
            $lang = "en";
    };
}

$translations = array(
    array(
        "lang" => "fr",
        "connect-db" => "Connecter votre base de donnée",
        "description-db" => "Sassiez dans les champs les informations de votre base de donnée. <br> Vous pouvez les trouver auprès de votre hébergeur web.",
        "namedb" => "Nom de la base de donnée",
        "hostdb" => "Chemin d'accès (host)",
        "hostindication" => "'localhost' dans la plupart des cas",
        "userdbindication" => "Vérifier que l'utilisateur possède les droits d'édition de la base de donnée",
        "userdb" => "Utilisateur de la base de donnée",
        "passworddb" => "Mot de passe de la base de donnée",
        "connect" => "Se connecter à la base de donnée",
        "failconnection" => "La connection à échouée",
        "retry" => "Réessayer avec d'autres identifiants",
        "error" => "Erreur : ",
        "errorfile" => "Une erreur est survenue lors de la création du fichier.",

        "adminaccount" => "Créer le compte administrateur",
        "email" => "Votre adresse email",
        "password" => "Saissisez un mot de passe",
        "passwordinstruction" => "Il doit faire plus de 12 caractères",
        "cta-create-account" => "Créer mon compte",

        "title-set" => "Personaliser votre site",
        "description" => "Sassiez dans les champs les informations de votre base de donnée. <br> Vous pouvez les trouver auprès de votre hébergeur web.",
        "sitename" => "Nom du site web",
        "structure" => "Choisissez la structure de votre site",
        "accent" => "Choisissez la couleur de votre thème",
        "corner" => "Choisissez l'apparence des boutons de votre site",
        "classic" => "Classique",
        "vertical" => "Vertical",
        "both" => "2 axes",
        "megamenu" => "Méga menu",
        "menufloating" => "Menu flotant",
        "none" => "Aucune structure",
        "teal" => "Saphir",
        "blue" => "Bleu",
        "deepblue" => "Marine",
        "purple" => "Mytille",
        "blue" => "Bleu",
        "bay" => "Baie",
        "orange" => "Orange",
        "yellow" => "Citron",
        "menthe" => "Menthe",
        "forest" => "Forêt",
        "chocolate" => "Chocolat",
        "grey" => "Gris",
        "dark" => "Sombre",
        "square" => "Carré",
        "neutral" => "Neutre",
        "smooth" => "Doux",
        "rounded" => "Arrondi",
        "fullrounded" => "Arrondi complet",
        "multilanguage" => "Votre site sera t'il en multilangues",
        "sale" => "Votre site sera t'il un site de vente ?",
        "cta" => "Créer le site",
        "homepage" => "Page d'accueil",
        "page404" => "Page non trouvée",

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
