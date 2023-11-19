<?php
require_once('../auth/isadmin.php');
global $con;
require_once('../auth/db_con.php');
if (empty($_POST['id']) || empty($_POST['series']) || empty($_POST['title']) || (!isset($_POST['number']) || $_POST['number'] < 0) || empty($_POST['url']) || empty($_POST['poster'])) {
    header('Location: ../manage-content?e=edatamissing');
    exit;
} else {
    $checkIfExist_query = "SELECT * FROM `episodes` WHERE `id` = ? OR (`series_id` = ? AND `ep_number` = ?)";
    $stmt = $con->prepare($checkIfExist_query);
    $stmt->bind_param("iii", $_POST['id'], $_POST['series'], $_POST['number']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header('Location: ../manage-content?e=episodenotexist');
        exit;
    }

    $isActive = $_POST['visible'] == 'on';
    $introStart = (60 * intval($_POST['start-minutes'])) + intval($_POST['start-seconds']);
    $introEnd = (60 * intval($_POST['end-minutes'])) + intval($_POST['end-seconds']);
    $url = "";
    $url2 = "";
    $url3 = "";
    if (isset($_POST['url']) && !empty($_POST['url'])) {
        $url = "$_POST[url]";
    }
    if (isset($_POST['url2']) && !empty($_POST['url2'])) {
        $url2 = "$_POST[url2]";
    }
    if (isset($_POST['url3']) && !empty($_POST['url3'])) {
        $url3 = "$_POST[url3]";
    }
    $updateEpisode_query = "UPDATE `episodes` SET `series_id` = ?, `url` = ?, `url2` = ?, `url3` = ?, `poster` = ?, `title` = ?, `ep_number` = ?, `isActive` = ?, `desc` = ?, `intro_start` = ?, `intro_end` = ? WHERE `id` = ?";
    $stmt = $con->prepare($updateEpisode_query);
    $stmt->bind_param("isssssiisiii", $_POST['series'], $url, $url2, $url3, $_POST['poster'], $_POST['title'], $_POST['number'], $isActive, $_POST['desc'], $introStart, $introEnd, $_POST['id']);

    if ($stmt->execute()) {
        header('Location: ../manage-content?s=eupdated');
    } else {
        header('Location: ../manage-content?e=error');
    }
    exit;
}
