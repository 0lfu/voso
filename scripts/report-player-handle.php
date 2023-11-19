<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/db_con.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['player-id'])) {
        $player_id = $_POST['player-id'];
        $checkReport_query = "SELECT * FROM `player_reports` WHERE `video_id` = $player_id";
        $existingReport = $con->query($checkReport_query);

        if ($existingReport->num_rows > 0) {
            header("Location: ../watch?v=" . $player_id . "&e=alrrep");
            exit;
        }
        $reported_by = $_SESSION['id'] ?? 0;
        $insertReport_query = "INSERT INTO `player_reports`(`video_id`, `reported_by`) VALUES ('$player_id', '$reported_by')";
        $result = $con->query($insertReport_query);

        if ($result) {
            header("Location: ../watch?v=" . $player_id . "&s=sucrep");
            exit;
        } else {
            header("Location: ../watch?v=" . $player_id . "&e=error");
            exit;
        }
    } else {
        header("Location: ../browse&e=error");
        exit;
    }
} else {
    header("location: /browse?e=unauthorized");
    exit;
}