<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../auth/isadmin.php');
global $con;
require_once('../auth/db_con.php');
if (empty($_POST['id']) || empty($_POST['altname']) || empty($_POST['fullname']) || empty($_POST['season']) || empty($_POST['epcount']) || empty($_POST['brdtype']) || empty($_POST['brdstart']) || empty($_POST['brdend']) || empty($_POST['genre']) || empty($_POST['desc']) || empty($_POST['poster'])) {
    header('Location: ../additem?e=sdatamissing');
    exit;
} else {
    $genres = $_POST['genre'];
    $genre_string = "";
    foreach ($genres as $genre) {
        $genre_string .= $genre . "; ";
    }
    $addSeries_query = "INSERT INTO `series`(`id`, `title`, `alt_title`, `season`, `poster`, `desc`, `genre`, `brd-type`, `brd-start`, `brd-end`, `ep_count`, `tags`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($addSeries_query);
    $stmt->bind_param("ississssssis", $_POST['id'], $_POST['fullname'], $_POST['altname'], $_POST['season'], $_POST['poster'], $_POST['desc'], $genre_string, $_POST['brdtype'], $_POST['brdstart'], $_POST['brdend'], $_POST['epcount'], $_POST['tags']);

    if ($stmt->execute()) {
        header('Location: ../additem?s=sadded');
    } else {
        header('Location: ../additem?e=error');
    }
    exit;
}
