<?php

session_start();

if (isset($_SESSION["id"]) && isset($_SESSION["role"])) {
    header("Location: ../");
    die();
}

?>
<!doctype html>
<?php

include "../../functions/data-base.php";

if ($isactivedb) {
    $sql = "SELECT * FROM `{$tableprefix}-collection-users` WHERE `c-role` = '1' LIMIT 1";
    $result = $pdo->query($sql);

    if ($result->rowCount() > 0) {
    } else {
        header("Location: ../../install/create-admin/");
        die();
    }
} else {
    header("Location: ../install");
    die();
}

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

$translations = array(
    array(
        "lang" => "fr",
        "title" => "Connectez vous",
        "description" => "Sassiez dans les champs les informations de votre base de donnée. <br> Vous pouvez les trouver auprès de votre hébergeur web.",
        "email" => "Votre adresse email",
        "password" => "Votre mot de passe",
        "incorrectpassword" => "Mot de passe incorrect.",
        "incorrectemail" => "Adresse e-mail incorrecte.",
        "cta" => "Se connecter",
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

$errormessage = "";

if (isset($_POST['email']) && isset($_POST['userpassword'])) {
    // get user info
    $sql = "SELECT * FROM `{$tableprefix}-collection-users` WHERE `c-email` = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errormessage = "<div role='alert'>" . $translation["incorrectemail"] . "</div>";
    } else {
        // check password
        if (password_verify($_POST['userpassword'] . $passwordsalt, $user['c-password'])) {
            session_start();

            // create variable
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $user['id'];
            $_SESSION["email"] = $user['c-email'];
            $_SESSION["role"] = $user['c-role'];
            $_SESSION["lang"] = $lang;
    
            // redrict user to dashboard
            header("Location: ../");
            exit();
        } else {
            $errormessage = "<div role='alert'>" . $translation["incorrectpassword"] . "</div>";
        }
    }
}

if (isset($_POST['email'])) {
    $autofillemail = $_POST['email'];
} else {
    $autofillemail = "";
}
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../admin-assets/collection.css">
    <link rel="icon" href="../admin-assets/favicon-collection.png" type="image/png">
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
            <img class="isdark" src="../admin-assets/logo-collection-d.svg" alt="Collection cms" />
            <img class="islight" src="../admin-assets/logo-collection-l.svg" alt="Collection cms" />
        </div>

        <section class="install-section">

            <?php

            // Create the main user

            if ($translation != null) {
                echo '<h1>' . $translation["title"] . '</h1>';
                echo '<form action="./" method="POST">

                 ' . $errormessage .

                    '<label for="email">' . $translation["email"] . '</label>
                    <input id="email" name="email" type="email" autocomplete="email" value="' . $autofillemail . '"required/>

                    <label for="userpassword">' . $translation["password"] . '</label>
                    <input id="userpassword" name="userpassword" type="password" autocomplete="current-password" minlength=4" required/>

                    <button type="submit">' . $translation["cta"] . '</button>
                    </form>';
            }

            ?>
        </section>
    </main>
</body>

</html>