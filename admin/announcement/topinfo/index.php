<?php
include("../../../functions/data-base.php");
session_start();

if ($isactivedb != true) {
    header("Location: ../../install");
    die();
}

if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
} else {
    header("Location: ../../login");
    die();
}

$sql = "SELECT * FROM `{$tableprefix}-collection-settings` WHERE `cpropriety` = 'sitename'";
$result = $pdo->query($sql);

if ($result->rowCount() > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if (!empty($row['cvalue'])) {
    } else {
        header("Location: ../../install/start/");
        die();
    }
} else {
    header("Location: ../../install/start/");
    die();
}

include("../../../components/topinfo.php");

$lang = $_SESSION["lang"];
echo '<html lang="' . $lang . '"';

$translations = array(
    array(
        "lang" => "fr",
        "title" => "Modifier le site",
        "page" => "Pages",
        "media" => "Médias",
        "announcement" => "Annonces",
        "setting" => "Paramètres",
        "component" => "Composants",
        "form" => "Formulaires"
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

if(isset($_POST["message"])) {
       //Create file database connection

       $dbfile = '../../components/topinfo.php';

       $contenu = '<?php' . PHP_EOL .
           '$isactivedb=true;' . PHP_EOL .
           '$tableprefix="' . $tableprefix . '";' . PHP_EOL .
           '$passwordsalt="' . $passwordsalt . '";' . PHP_EOL .
           '$host="' . $host . '";' . PHP_EOL .
           '$db="' . $db . '";' . PHP_EOL .
           '$user="' . $user . '";' . PHP_EOL .
           '$passwd="' . $passwd . '";' . PHP_EOL .
           PHP_EOL .
           'if ($isactivedb == false) {' . PHP_EOL .
           '} else {' . PHP_EOL .
           'try {' . PHP_EOL .
           '    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));' . PHP_EOL .
           '    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);' . PHP_EOL .
           '} catch(Exception $e) {' . PHP_EOL .
           '    echo "Erreur : ".$e->getMessage()."<br />";' . PHP_EOL .
           '}' . PHP_EOL .
           '}' . PHP_EOL .
           '?>';

       if (file_put_contents($dbfile, $contenu) !== false) {

       } else {
        
       };

}

?>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../../admin-assets/collection.css">
    <link rel="icon" href="../../admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["title"] ?></title>
    <style>
        .install-section {
            padding: 24px;
            border-radius: 8px;
            background-color: var(--surface-secondary);
            margin-top: 24px;
            max-width: 820px;
        }

        .install-section h1 {
            font-size: 32px;
        }

        .logo-collection img {
            display: block;
            margin: 64px auto;
            max-height: 64px;
        }

        nav.admin li a {
            display: flex;
            gap: 6px;
            flex-direction: row;
            border-bottom: 0;
            align-items: center;
        }

        nav.admin ul {
            display: flex;
            gap: 12px;
            flex-direction: column;
        }

        .icon-admin img {
            height: 28px;
            width: 28px;
            margin: 0px;
        }

        nav.admin li::marker {
            content: '';
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

        h1 {
            font-size: 32px;
        }

        #demo {
            padding: 8px 12px;
            max-width: 300px;
            text-align: center;
            border-radius: 8px;
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
</head>

<body>
    <div class="adminpage">
        <?php
        $access = "../../";
        include("../../../functions/comp-navadmin.php");

        $yesterday = date('Y-m-d', strtotime('0 day'));
        $tomorrow = date('Y-m-d', strtotime('0 day'));

        ?>

        <div class="admincontent">
            <main>
                <h1>Top info banner</h1>
                <form action="" method="post">
                    <div aria-hidden="true" style="height: 24px;"></div>
                    <?php 
                    if($tiactive == 1){
                        echo ' <input type="checkbox" role="switch" id="active" checked/>
                        <label for="active"> Active banner</label>';
                    } else {
                        echo ' <input type="checkbox" role="switch" id="active" />
                        <label for="active"> Active banner</label>';
                    }
                    ?>
            
                    <div aria-hidden="true" style="height: 12px;"></div>

                    <label for="message">Your message </label>
                    <p>Limited to 80 caract</p>
                    <textarea id="message" rows="4" maxlength="80"><?= $timessage ?></textarea>
                    <div aria-hidden="true" style="height: 24px;"></div>
                    <label for="address">Link address <span>optionnal</span></label>
                    <input type="url" name="address" id="sdate" value="<?= $tiurl ?>" />
                    <?php 
                    if($titab == 1){
                        echo ' <input type="checkbox" role="switch" id="newtab" checked/>
                        <label for="newtab"> Open in a new tab</label>';
                    } else {
                        echo ' <input type="checkbox" role="switch" id="newtab" />
                        <label for="newtab"> Open in a new tab</label>';
                    }
                    ?>

                    <div aria-hidden="true" style="height: 24px;"></div>

                    <label for="sdate">Start date <span>optionnal</span></label>
                    <input type="date" name="sdate" min="<?php echo $yesterday; ?>" id="sdate" />

                    <label for="edate">End date <span>optionnal</span></label>
                    <input type="date" name="edate" min="<?php echo $tomorrow; ?>" id="edate" />

                    <div aria-hidden="true" style="height: 24px;"></div>

                    <fieldset>
                <legend> Couleur du bandeau</legend>
                <div style="display:flex; flex-direction:row; flex-wrap:wrap;gap:8px;">
                
                <label class="rich accent"><input type="radio" name="accent" value="#158796" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#158796;"></div>  
                </div>
                teal</label>

                <label class="rich accent"><input type="radio" name="accent" value="#147eb4" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#147eb4;"></div>  
                </div>
                Blue</label>

                <label class="rich accent"><input type="radio" name="accent" value="#17205e" required>
                <div class="visual accent">
                    <div class="palette" style="background-color:#17205e;"></div>  
                </div>
                 Deepblue</label>

                </fieldset>


                    <input type="submit" value="Save" />
                </form>
                <script>

                    const demoElement = document.getElementById('demo');
                    const fontColorInput = document.getElementById('fcolor');
                    const bgColorInput = document.getElementById('bgcolor');

                    fontColorInput.addEventListener('input', updateDemo);
                    bgColorInput.addEventListener('input', updateDemo);

                    function updateDemo() {
                        const fontColor = fontColorInput.value;
                        const bgColor = bgColorInput.value;

                        demoElement.style.color = fontColor;
                        demoElement.style.backgroundColor = bgColor;
                    }
                </script>
            </main>
            <footer>
                Collection 0.4
            </footer>

        </div>
    </div>
</body>

</html>