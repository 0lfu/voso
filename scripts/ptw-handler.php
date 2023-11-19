<?php
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header("location: /browse?e=unauthorized");
    exit;
}
require_once '../auth/isloggedin.php';
require_once '../auth/db_con.php';
if (isset($_POST['series_id'])) {
    $user_id = $_SESSION['id'];
    $series_id = $_POST['series_id'];
    $query = $con->prepare('SELECT * FROM `plan_to_watch` WHERE `plan_to_watch`.`series_id` = ? AND `plan_to_watch`.`user_id` = ?');
    $query->bind_param('ii', $series_id, $user_id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        $query = $con->prepare('DELETE FROM `plan_to_watch` WHERE `plan_to_watch`.`series_id` = ? AND `plan_to_watch`.`user_id` = ?');
        $query->bind_param('ii', $series_id, $user_id);
        $query->execute();
        echo json_encode(['status' => 'removed']);
    } else {
        $query = $con->prepare('INSERT INTO `plan_to_watch` (`series_id`, `user_id`) VALUES (?, ?)');
        $query->bind_param('ii', $series_id, $user_id);
        $query->execute();
        echo json_encode(['status' => 'added']);
    }
} else {
    echo json_encode(['status' => 'error']);
}
