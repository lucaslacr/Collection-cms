<?php
$isactivedb=true;
$tableprefix="rtj9wh0jgxs123";
$passwordsalt="xcjlskpqorpr1g";
$host="localhost";
$db="collection";
$user="admin";
$passwd="pommepomme";

if ($isactivedb == false) {
} else {
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
    echo "Erreur : ".$e->getMessage()."<br />";
}
}
?>