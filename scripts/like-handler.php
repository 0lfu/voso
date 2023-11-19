<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header("location: /browse?e=unauthorized");
    exit;
}
require_once '../auth/db_con.php';
if (!isset($_SESSION['loggedin'])) {
    echo json_encode(['status' => 'notloggedin']);
} else {
    if (isset($_POST['video_id'])) {
        $user_id = $_SESSION['id'];
        $video_id = $_POST['video_id'];
        $query = $con->prepare('SELECT * FROM `likes` WHERE `likes`.`video_id` = ? AND `likes`.`user_id` = ?');
        $query->bind_param('ii', $video_id, $user_id);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            $query = $con->prepare('DELETE FROM `likes` WHERE `likes`.`video_id` = ? AND `likes`.`user_id` = ?');
            $query->bind_param('ii', $video_id, $user_id);
            $query->execute();
            echo json_encode(['status' => 'unliked']);
        } else {
            $query = $con->prepare('INSERT INTO likes (video_id, user_id) VALUES (?, ?)');
            $query->bind_param('ii', $video_id, $user_id);
            $query->execute();
            echo json_encode(['status' => 'liked']);
        }
    } else {
        echo json_encode(['status' => 'error']);
    }
}
