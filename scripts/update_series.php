<?php
require_once('../auth/isadmin.php');
global $con;
require_once('../auth/db_con.php');

if (empty($_POST['id']) || empty($_POST['altname']) || empty($_POST['fullname']) || empty($_POST['season']) || empty($_POST['epcount']) || empty($_POST['brdtype']) || empty($_POST['brdstart']) || empty($_POST['brdend']) || empty($_POST['genre']) || empty($_POST['desc']) || empty($_POST['poster'])) {
    header('Location: ../manage-content?e=sdatamissing');
    exit;
} else {
    $checkIfExist_query = "SELECT * FROM `series` WHERE `id` = ?";
    $stmt = $con->prepare($checkIfExist_query);
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header('Location: ../manage-content?e=seriesnotexist');
        exit;
    }

    $genres = $_POST['genre'];
    $genre_string = "";
    foreach ($genres as $genre) {
        $genre_string .= $genre . "; ";
    }

    $updateSeries_query = "UPDATE `series` SET `title` = ?, `alt_title` = ?, `season` = ?, `poster` = ?, `desc` = ?, `genre` = ?, `brd-type` = ?, `brd-start` = ?, `brd-end` = ?, `ep_count` = ?, `tags` = ? WHERE `id` = ?";
    $stmt = $con->prepare($updateSeries_query);
    $stmt->bind_param("ssissssssisi", $_POST['fullname'], $_POST['altname'], $_POST['season'], $_POST['poster'], $_POST['desc'], $genre_string, $_POST['brdtype'], $_POST['brdstart'], $_POST['brdend'], $_POST['epcount'], $_POST['tags'], $_POST['id']);

    if ($stmt->execute()) {
        header('Location: ../manage-content?s=supdated');
    } else {
        header('Location: ../manage-content?e=error');
    }
    exit;
}
