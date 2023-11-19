<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
global $con;
require_once('../auth/db_con.php');
require_once('../auth/isadmin.php');

if (
    empty($_POST['series']) ||
    empty($_POST['videolinks']) ||
    empty($_POST['thumbnailinks']) ||
    empty($_POST['titles'])
) {
    header('Location: ../additem?e=mdatamissing');
} else {
    $series_id = $_POST['series'];
    $videolinks = explode(PHP_EOL, $_POST['videolinks']);
    $thumbnailinks = explode(PHP_EOL, $_POST['thumbnailinks']);
    $titles = explode(PHP_EOL, $_POST['titles']);

    if (count($videolinks) !== count($thumbnailinks) || count($videolinks) !== count($titles)) {
        header('Location: ../additem?e=mdatainvalid');
        exit;
    }

    $getLastEpisodeNumberQuery = "SELECT MAX(ep_number) as max_ep_number FROM episodes WHERE series_id = ?";
    $stmt = $con->prepare($getLastEpisodeNumberQuery);
    $stmt->bind_param("i", $series_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $lastEpisodeNumber = $row['max_ep_number'];

    if ($lastEpisodeNumber === null) {
        $lastEpisodeNumber = 0;
    }

    $addEpisode_query = "INSERT INTO `episodes`(`id`, `series_id`, `url`, `url2`, `url3`, `poster`, `title`, `ep_number`, `isActive`) VALUES (?, ?, ?, '', '', ?, ?, ?, 1)";

    $stmt = $con->prepare($addEpisode_query);
    $stmt->bind_param("iisssi", $nextID, $series_id, $url, $poster, $title, $ep_number);

    for ($i = 0; $i < count($videolinks); $i++) {
        include_once('generate_uid.php');
        $nextID = generate_unique_id($con);

        $url = $videolinks[$i];
        $poster = $thumbnailinks[$i];
        $title = $titles[$i];
        $ep_number = $lastEpisodeNumber + 1 + $i;
        if (!$stmt->execute()) {
            header('Location: ../additem?e=error');
            exit;
        }
    }
    header('Location: ../additem?s=madded');
}
exit;
