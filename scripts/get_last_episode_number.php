<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/isloggedin.php';
require_once '../auth/isadmin.php';
require_once '../auth/db_con.php';


if (isset($_GET['series_id'])) {
    $series_id = $_GET['series_id'];

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

    $stmt->close();
    $con->close();

    echo $lastEpisodeNumber;
} else {
    echo "0";
}

