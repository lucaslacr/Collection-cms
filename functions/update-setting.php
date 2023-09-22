<?php
if (isset($_POST["sitename"])) {
   
    $sitename = $_POST["sitename"];
    $sitelanguage = $_POST['sitelanguage'];
  
    $sitename = addslashes($sitename);
    $sitelanguage = addslashes($sitelanguage);
   
    include("data-base.php");
    $sql = "UPDATE `collectionsetting` SET `id`='1',`sitename`=:sitename,`sitelanguage`=:sitelanguage WHERE 1; ";
    $query = $pdo->prepare($sql); // Etape 1 : Préparation de la requête
 
    $query->bindValue(':sitename', $sitename); 
    $query->bindValue(':sitelanguage', $sitelanguage); 
   
    $query->execute();
    echo "Votre message a été envoyé<br>";
 
 } else {
    // Faites quelque chose en cas de non-détection de $_POST["sitename"]
 }?>