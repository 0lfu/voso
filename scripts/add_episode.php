<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
global $con;
require_once('../auth/db_con.php');
require_once('../auth/isadmin.php');
if (
    empty($_POST['id']) ||
    empty($_POST['series']) ||
    empty($_POST['title']) ||
    !isset($_POST['number']) || // Ensure 'number' is set
    ($_POST['number'] !== '' && intval($_POST['number']) < 0) || // Check if 'number' is a non-negative integer
    empty($_POST['url']) ||
    empty($_POST['poster'])
) {
    header('Location: ../additem?e=edatamissing');
    exit;
} else {
    $checkIfExist_query = "SELECT * FROM `episodes` WHERE `episodes`.`id` = ? OR (`episodes`.`series_id` = ? AND `episodes`.`ep_number` = ?)";
    $stmt = $con->prepare($checkIfExist_query);
    $stmt->bind_param("iii", $_POST['id'], $_POST['series'], $_POST['number']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: ../additem?e=eexist');
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

    $addEpisode_query = "INSERT INTO `episodes`(`id`, `series_id`, `url`, `url2`, `url3`, `poster`, `title`, `ep_number`, `isActive`, `desc`, `intro_start`, `intro_end`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($addEpisode_query);
    $stmt->bind_param("iisssssiisii", $_POST['id'], $_POST['series'], $url, $url2, $url3, $_POST['poster'], $_POST['title'], $_POST['number'], $isActive, $_POST['desc'], $introStart, $introEnd);

    if ($stmt->execute()) {
        header('Location: ../additem?s=eadded');
    } else {
        header('Location: ../additem?e=error');
    }
    exit;
}
