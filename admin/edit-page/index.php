<?php
        include("../../functions/data-base.php");
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../collection.css">
    <title>Edit page - Collection</title>
</head>
<body>
    <main>
        <h1>Edit page {page name}</h1>
        <form action="#" method="POST">
        <label for="titlepage">Page title</label>
        <input type="text" name="titlepage" id="titlepage" required="" minlength="20" maxlength="60">

        <label for="descriptionpage">Your message </label>
        <textarea id="descriptionpage" rows="5" minlength="110" maxlength="150"></textarea>
        
        <input type="checkbox" role="switch" id="robotpage">
        <label for="robotpage"> Page visible for search engine</label>

        <button type="submit">Save changes</button>
        </form>
    </main>
</body>

</html>