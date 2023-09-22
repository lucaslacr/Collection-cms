<?php
        include("../../functions/data-base.php");
        include("../../functions/update-setting.php");
        include("../../functions/get-setting.php");
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../collection.css">
    <title>Setting - Collection</title>
</head>
<body>
    <main>
        <h1>Setting website</h1>
        <form action="#" method="POST">
            <div class="ensemble"><label for="sitename">Site name</label><br><input type="texte" id="sitename" name="sitename" value="<?= $setting['sitename'] ?>" maxlength="350" required=""></div>
            <label for="cars">Choose a car:</label>
            <select name="sitelanguage" id="sitelanguage">
                <optgroup label="Popular">
                    <option value="en">English</option>
                    <option value="fr">French</option>
                </optgroup>
             <!--   <optgroup label="German Cars">
                    <option value="mercedes">Mercedes</option>
                    <option value="audi">Audi</option>
                </optgroup> -->
            </select>
            <legend>more than 12 caract</legend>
            </div>
            <button type="submit">Save changes</button>
        </form>
    </main>
</body>

</html>