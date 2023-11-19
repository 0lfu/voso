<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!defined('like-module')) {
    header("location: /browse?e=unauthorized");
    exit;
}
require_once 'auth/db_con.php';
$query = $con->prepare('SELECT * FROM `likes` WHERE `likes`.`video_id` = ? AND `likes`.`user_id` = ?');
$query->bind_param('ii', $id, $_SESSION['id']);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    echo "<span class='fas fa-heart interactive-item like-button' data-video-id='$id'></span>";
} else {
    echo "<span class='far fa-heart interactive-item like-button' data-video-id='$id'></span>";
}
