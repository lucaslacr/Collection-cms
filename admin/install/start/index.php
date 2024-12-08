<!doctype html>
<?php

include "../../../functions/data-base.php";

if ($isactivedb) {
    $sql = "SELECT * FROM `{$tableprefix}-collection-users` WHERE `c-role` = '1' LIMIT 1";
    $result = $pdo->query($sql);

    if ($result->rowCount() > 0) {
         
    } else {
        header("Location: ../create-admin/");
        die();
    }
} else {
    header("Location: ../");
    die();
}

session_start();

if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
    if ($_SESSION["role"] != 1) {
        header("Location: ../../login");
        die();
    }
} else {
    header("Location: ../../login");
    die();
}

$lang = $_SESSION["lang"];
echo '<html lang="' . $lang . '"';

if (isset($_POST["sitename"])) {
    echo "ok " . $_POST["sitename"];
    // Add start setting to database
    $sql = "INSERT INTO `{$tableprefix}-collection-settings` 
    (`id`, `c-main-language`, `c-all-languages`, `c-name`, `c-url`, `c-cookies`, `c-index`) 
    VALUES (:id, :mainlanguage, '', :cname, :curl, 0, 1)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', 0, PDO::PARAM_INT);
    $stmt->bindValue(':mainlanguage', $lang, PDO::PARAM_STR);
    $stmt->bindValue(':cname', $_POST["sitename"], PDO::PARAM_STR);
    $stmt->bindValue(':curl', $_SERVER['SERVER_NAME'], PDO::PARAM_STR);
    if ($stmt->execute()) {
        // Insertion réussie
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Erreur lors de l'insertion dans la table `{$tableprefix}-collection-settings` : " . $errorInfo[2];
    }
    
    // Add table for appearance
    $sql = "INSERT INTO `{$tableprefix}-collection-apparence` 
    (`id`, `c-logo`, `c-favicon`, `c-color`, `c-textcolor`, `c-ncolor`, `c-palette`, `c-corner`, `c-title`, `c-body`, `c-structure`, `c-header`, `c-footer`) 
    VALUES (:id, :logo, :favicon, :color, :textcolor, :ncolor, :palette, :corner, :title, :body, :structure, :header, :footer)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', 0, PDO::PARAM_INT);
    $stmt->bindValue(':logo', '', PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    $stmt->bindValue(':favicon', '', PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    $stmt->bindValue(':color', $_POST["accent"], PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    $stmt->bindValue(':textcolor', '', PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    $stmt->bindValue(':ncolor', 0, PDO::PARAM_INT); // Remplacez 0 par la valeur que vous souhaitez insérer
    $stmt->bindValue(':palette', 1, PDO::PARAM_INT); // Remplacez 1 par la valeur que vous souhaitez insérer
    $stmt->bindValue(':corner', $_POST["corner"], PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    $stmt->bindValue(':title', '', PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    $stmt->bindValue(':body', '', PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    $stmt->bindValue(':structure', $_POST["structure"], PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    $stmt->bindValue(':header', 1, PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    $stmt->bindValue(':footer', 1, PDO::PARAM_STR); // Remplacez '' par la valeur que vous souhaitez insérer
    if ($stmt->execute()) {
        // Insertion réussie
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Erreur lors de l'insertion dans la table `{$tableprefix}-collection-apparence` : " . $errorInfo[2];
    }
}


$translations = array(
    array(
        "lang" => "fr",
        "title" => "Personaliser votre site",
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

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../../admin-assets/collection.css">
    <link rel="icon" href="../../admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["title"] ?></title>
    <style>
        .start-section {
            padding: 24px;
            border-radius: 8px;
            background-color: var(--surface-secondary);
            margin-top: 24px;
            max-width: 820px;
        }

        .start-section h1 {
            font-size: 32px;
        }

        .logo-collection img {
            display: block;
            margin: 64px auto;
            max-height: 64px;
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
    <main>
        <div class="logo-collection">
            <img class="isdark" src="../../admin-assets/logo-collection-d.svg" alt="Collection cms" />
            <img class="islight" src="../../admin-assets/logo-collection-l.svg" alt="Collection cms" />
        </div>

        <section class="start-section">
            <?php

            // The start page here

            if ($translation != null) {

                echo '
                <style>
                label.rich:has(input[type="radio"])  {
                    width:100%;
                    display:flex;
                    flex-direction:column;
                    max-width: 280px;
                    text-align:center;
                    padding:5px;
                    border-radius:8px;
                    border: 2px solid var(--line-decorative) !important;
                }
                label.accent  {
                    max-width: 120px !important;
                }

                label.rich:has(input[type="radio"]:checked) {
                    border: 2px solid var(--text-title) !important;
                }
                label.rich:has(input[type="radio"])::before {
                    content: none;
                  }

                  .visual {
                    display: flex;
                    justify-content:center;
                    align-items:center;
                    height:120px;
                    width:100%;
                  }

                  .accent .visual {
                    height:80px;
                    width:100%;
                  }

                  .palette {
                    border-radius:99px; 
                    height:48px;
                    width:48px;
                   }

                  .start-section {
                    padding: 24px;
                    border-radius: 8px;
                    background-color: var(--surface-secondary);
                    margin-top: 24px;
                    max-width: 906px;
                  }
                </style>
                
                ';

                echo '<h1>' . $translation["title"] . '</h1> <p>' . $translation["description"] . ' </p>';
                echo '<form action="./" method="POST">
              
                <label for="sitename">' . $translation["sitename"] . '</label>
                <input id="sitename" name="sitename" autocomplete="organization" type="text" required/>

                <div aria-hidden="true" style="height:24px;"></div>

                <fieldset>
                    <legend>' . $translation["structure"] . '</legend>
                    <div style="display:flex; flex-direction:row; flex-wrap:wrap;gap:8px;">
                    <label class="rich"><input type="radio" name="structure" value="1" required>
                    <div class="visual">
                        <div style="border: 1px solid var(--line-accessible); width:120px; width: 164px; padding:4px; height: 88px; border-radius:4px;">
                            <div style="width:100%; height:10px; border-radius:4px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                        </div>  
                    </div>
                    ' . $translation["classic"] . '</label>
                    <label class="rich"><input type="radio" name="structure" value="2" required>
                    <div class="visual">
                        <div style="border: 1px solid var(--line-accessible); width:120px; width: 164px; padding:4px; height: 88px; border-radius:4px;">
                            <div style="width:40px; height:100%; border-radius:4px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                        </div>  
                    </div>
                    ' . $translation["vertical"] . '</label>
                    <label class="rich"><input type="radio" name="structure" value="3" required>
                    <div class="visual">
                        <div style="border: 1px solid var(--line-accessible); width:120px; width: 164px; padding:4px; height: 88px; border-radius:4px;">
                        <div style="width:100%; height:10px; border-radius:4px;  background-color:var(--line-accessible); opacity:0.6;"></div>

                        <div style="width:40px; height:80%; margin-top:4px;border-radius:4px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                        </div>  
                    </div>
                    ' . $translation["both"] . '</label>
                    <label class="rich"><input type="radio" name="structure" value="4" required>
                    <div class="visual">
                        <div style="border: 1px solid var(--line-accessible); width:120px; width: 164px; padding:4px; height: 88px; border-radius:4px;">
                            <div style="width:100%; height:10px; border-radius:4px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                            <div style="margin-top:4px;width:100%; height:30px; border-radius:4px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                        </div>  
                    </div>
                    ' . $translation["megamenu"] . '</label>
                    <label class="rich"><input type="radio" name="structure" value="5" required>
                    <div class="visual">
                        <div style="border: 1px solid var(--line-accessible); width:120px; width: 164px; padding:4px; height: 88px; border-radius:4px;">
                            <div style="width:40px; height:10px; border-radius:4px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                        </div>  
                    </div>
                    ' . $translation["menufloating"] . '</label>
                    <label class="rich"><input type="radio" name="structure" value="6" required>
                    <div class="visual">
                        <div style="border: 1px solid var(--line-accessible); width:120px; width: 164px; padding:4px; height: 88px; border-radius:4px;">
                        </div>  
                    </div>
                    ' . $translation["none"] . '</label>
                    </div>
                </fieldset>

                <fieldset>
                <legend>' . $translation["accent"] . '</legend>
                <div style="display:flex; flex-direction:row; flex-wrap:wrap;gap:8px;">
                
                <label class="rich accent"><input type="radio" name="accent" value="#158796" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#158796;"></div>  
                </div>
                ' . $translation["teal"] . '</label>

                <label class="rich accent"><input type="radio" name="accent" value="#147eb4" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#147eb4;"></div>  
                </div>
                ' . $translation["blue"] . '</label>

                <label class="rich accent"><input type="radio" name="accent" value="#17205e" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#17205e;"></div>  
                </div>
                ' . $translation["deepblue"] . '</label>

                <label class="rich accent"><input type="radio" name="accent" value="#41286c" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#41286c;"></div>  
                </div>
                ' . $translation["purple"] . '</label>

                <label class="rich accent"><input type="radio" name="accent" value="#bd2b5e" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#bd2b5e;"></div>  
                </div>
                ' . $translation["bay"] . '</label>
                <label class="rich accent"><input type="radio" name="accent" value="#f78c6b" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#f78c6b;"></div>  
                </div>
                ' . $translation["orange"] . '</label>
                <label class="rich accent"><input type="radio" name="accent" value="#ffd166" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#ffd166;"></div>  
                </div>
                ' . $translation["yellow"] . '</label>
                <label class="rich accent"><input type="radio" name="accent" value="#06d6a0" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#06d6a0;"></div>  
                </div>
                ' . $translation["menthe"] . '</label>

                <label class="rich accent"><input type="radio" name="accent" value="#1a5b3b" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#1a5b3b;"></div>  
                </div>
                ' . $translation["forest"] . '</label>

                <label class="rich accent"><input type="radio" name="accent" value="#462720" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#462720;"></div>  
                </div>
                ' . $translation["chocolate"] . '</label>

                <label class="rich accent"><input type="radio" name="accent" value="#55575f" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#55575f;"></div>  
                </div>
                ' . $translation["grey"] . '</label>

                <label class="rich accent"><input type="radio" name="accent" value="#0d0f17" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#0d0f17;"></div>  
                </div>
                ' . $translation["dark"] . '</label>

                </div>
                </fieldset>


                <fieldset>
                <legend>' . $translation["corner"] . '</legend>
                <div style="display:flex; flex-direction:row; flex-wrap:wrap;gap:8px;">

                <label class="rich"><input type="radio" name="corner" value="0" required>
                    <div class="visual">
                        <div style="width: 164px;
                        height: 48px; border-radius:0px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                    </div>
                    ' . $translation["square"] . '</label>

                    <label class="rich"><input type="radio" name="corner" value="4" required>
                    <div class="visual">
                        <div style="width: 164px;
                        height: 48px; border-radius:4px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                    </div>
                    ' . $translation["neutral"] . '</label>

                    <label class="rich"><input type="radio" name="corner" value="8" required>
                    <div class="visual">
                        <div style="width: 164px;
                        height: 48px; border-radius:8px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                    </div>
                    ' . $translation["smooth"] . '</label>

                    <label class="rich"><input type="radio" name="corner" value="16" required>
                    <div class="visual">
                        <div style="width: 164px;
                        height: 48px; border-radius:16px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                    </div>
                    ' . $translation["rounded"] . '</label>

                    <label class="rich"><input type="radio" name="corner" value="99" required>
                    <div class="visual">
                        <div style="width: 164px;
                        height: 48px; border-radius:99px;  background-color:var(--line-accessible); opacity:0.6;"></div>
                    </div>
                    ' . $translation["fullrounded"] . '</label>
                </div>
                </fieldset>

                <div aria-hidden="true" style="height:8px;"></div>

                <input type="checkbox" role="switch" id="multi" />
                <label for="multi">' . $translation["multilanguage"] . '</label>

                <input type="checkbox" role="switch" id="sale" />
                <label for="sale"> ' . $translation["sale"] . '</label>

                <div aria-hidden="true" style="height:24px;"></div>

                <button type="submit">' . $translation["cta"] . '</button>
            </form>';
            }
            ?>
        </section>
    </main>
</body>

</html>