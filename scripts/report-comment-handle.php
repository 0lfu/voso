<?php
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header("location: /browse?e=unauthorized");
    exit;
}
require_once '../auth/isloggedin.php';
require_once '../auth/db_con.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment-id'];

    $checkReport_query = "SELECT * FROM `comments_reports` WHERE `reported_by` = $_SESSION[id] AND `comment_id` = $comment_id";
    $existingReport = $con->query($checkReport_query);

    if ($existingReport->num_rows > 0) {
        echo json_encode(array("status" => "error", "message" => "You have already made this comment."));
        exit;
    }
    $getAuthor_query = "SELECT `author_id` FROM `comments` WHERE `id` = '$comment_id'";
    $result = $con->query($getAuthor_query);
    $res = $result->fetch_assoc();
    $author_id = $res['author_id'];
    $reason = $_POST['reason'];
    $note = $_POST['note'];

    $insertReport_query = "INSERT INTO `comments_reports`(`user_id`, `comment_id`, `reason`, `note`, `reported_by`) VALUES ('$author_id','$comment_id', '$reason', '$note', '$_SESSION[id]')";
    $result = $con->query($insertReport_query);

    if ($result) {
        echo json_encode(array("status" => "success", "message" => "Successfully reported comment."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to report comment. Unknown error."));
    }
}
