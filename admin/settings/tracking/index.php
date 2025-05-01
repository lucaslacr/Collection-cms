<?php
 $access ="../../";
include($access . "../functions/data-base.php");
session_start();

if ($isactivedb != true) {
    header("Location:" . $access . "install");
    die();
}

if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
} else {
    header("Location: " . $access . "login");
    die();
}

$sql = "SELECT * FROM `{$tableprefix}-collection-settings` WHERE `cpropriety` = 'sitename'";
$result = $pdo->query($sql);

if ($result->rowCount() > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if (!empty($row['cvalue'])) {
       
    } else {
        header("Location:  " . $access . "install/start/");
        die();
    }
} else {
    header("Location: " . $access . "install/start/");
    die();
}

include($access."translation.php");

$lang = $_SESSION["lang"];
echo '<html lang="' . $lang . '"';

?>
<html lang="<?= $setting['sitelanguage'] ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="<?= $access ?>admin-assets/collection.css">
    <link rel="icon" href="<?= $access ?>admin-assets/favicon-collection.png" type="image/png">
    <title><?php echo $translation["scriptcompliance"] ?></title>
</head>

<body>
    <div class="adminpage">
    <?php
      
       include($access . "../functions/comp-navadmin.php");
       
       ?>
        <div class="admincontent">
            <main>
            <h1><?=  $translation["scriptcompliance"] ?></h1>
                
           

            <form action="" method="post">
                    <div aria-hidden="true" style="height: 24px;"></div>
                     <input type="checkbox" role="switch" id="active" checked="">
                        <label for="active"> Active tracking</label>            
                    <div aria-hidden="true" style="height: 12px;"></div>

                    <label for="legit">Legit tracking script</label>
                    <p>Only tracker are essential to your website or services how didn't use personal information as ip adress.</p>
                    <textarea id="legit" rows="4" maxlength="500"> </textarea>
                    <div aria-hidden="true" style="height: 24px;"></div>
                    
                    
                    <label for="ads">Ads Tracking script</label>
                    <p>As Google Analytics, Criteo, Outbrain...</p>
                    <textarea id="ads" rows="4" maxlength="500">Promotion sur le cannapé-lit</textarea>
                    <div aria-hidden="true" style="height: 24px;"></div>


                    <label for="mesure">Mesurement only</label>
                    <p>Content Square, Matomo, Clarity, Sentry</p>
                    <textarea id="mesure" rows="4" maxlength="500">Promotion sur le cannapé-lit</textarea>
                    <div aria-hidden="true" style="height: 24px;"></div>


                    <label for="mesure">Personalisation only</label>
                    <p>Script and cookies about personalization not ads</p>
                    <textarea id="mesure" rows="4" maxlength="500">Promotion sur le cannapé-lit</textarea>
                    <div aria-hidden="true" style="height: 24px;"></div>



                    <input type="submit" value="Save">
                </form>

            </main>
            <footer>
                Collection 0.4
            </footer>

        </div>
    </div>
</body>

</html>

