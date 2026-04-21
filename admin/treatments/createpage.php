<?php

include("../../functions/data-base.php");
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titlepage = $_POST['titlepage'];
    $path = $_POST['path'];

    // Generate Slug
    $slug = preg_replace('~[^\pL\d]+~u', '-', $titlepage);
    $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
    $slug = preg_replace('~[^-\w]+~', '', $slug);
    $slug = trim($slug, '-');
    $slug = preg_replace('~-+~', '-', $slug);
    $slug = strtolower($slug);

    $sql = "INSERT INTO `{$tableprefix}-collection-pages` 
        (`id`, `ctitle`, `cdescription`, `cslug`, `cpath`, `chtml`, `ceditor`, `csearchvisibility`, `cvisitoracess`, `cowner`, `cpreview`, `clangkey`, `clang`)
        VALUES (:id, :ctitle, :cdescription, :cslug, :cpath, :chtml, :ceditor, :csearchvisibility, :cvisitoracess, :cowner, :cpreview, :clangkey, :clang)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', 0, PDO::PARAM_INT);
    $stmt->bindValue(':ctitle', $titlepage, PDO::PARAM_STR);
    $stmt->bindValue(':cdescription', "", PDO::PARAM_STR);
    $stmt->bindValue(':cslug', $slug, PDO::PARAM_STR);
    $stmt->bindValue(':cpath', $path, PDO::PARAM_STR);
    $stmt->bindValue(':chtml', "", PDO::PARAM_STR);
    $stmt->bindValue(':ceditor', "", PDO::PARAM_STR);
    $stmt->bindValue(':csearchvisibility', 0, PDO::PARAM_INT);
    $stmt->bindValue(':cvisitoracess', "", PDO::PARAM_STR);
    $stmt->bindValue(':cowner', $_SESSION["id"], PDO::PARAM_INT);
    $stmt->bindValue(':cpreview', "", PDO::PARAM_STR);
    $stmt->bindValue(':clangkey', "", PDO::PARAM_STR);
    $stmt->bindValue(':clang', "", PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Récupérer l'ID de la dernière page insérée
        $pageId = $pdo->lastInsertId();

        // Rediriger vers l'éditeur
        header("Location: ../pages/edit?p=" . $pageId);
        exit();
    } else {
        // Gestion d'erreur en cas d'échec
        echo "Erreur lors de la création de la page";
    }
}
