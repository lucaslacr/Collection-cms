<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="../collection.css">
    <title>Setting</title>
</head>

<body>
    <main>
        <h1>Setting website</h1>
        <form action="https://avec.lucaslacroix.com/nouvelle-ressource/" method="POST">
            <div class="ensemble"><label for="lien">Site name</label><br><input type="texte" id="sitename" name="sitename" maxlength="350" required=""></div>
            <label for="cars">Choose a car:</label>
            <select name="cars" id="cars">
                <optgroup label="Popular">
                    <option value="volvo">English</option>
                    <option value="saab">French</option>
                </optgroup>
                <optgroup label="German Cars">
                    <option value="mercedes">Mercedes</option>
                    <option value="audi">Audi</option>
                </optgroup>
            </select>
            <legend>more than 12 caract</legend>
            </div>
            <button type="submit">Save changes</button>
        </form>
    </main>
</body>

</html>