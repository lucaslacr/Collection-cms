<?php
if (isset($_SESSION["id"]) && isset($_SESSION["role"])) {

} else {
    header("Location: ../");
    die();
}
?>