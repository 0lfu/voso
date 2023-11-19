<?php
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header("location: /browse?e=unauthorized");
    exit;
}
require_once '../auth/isloggedin.php';
require_once '../auth/db_con.php';

$user_id = $_POST['uid'];
$username = $_POST['username'] ?? '';
$avatar = $_POST['avatar'] ?? '';
$background = $_POST['background'] ?? '';
$description = $_POST['description'] ?? '';

if (strlen($username) < 3) {
    echo "Username must be at least 3 characters long";
    return;
}

$bannedUsernames = array("admin", "administrator", "root", "superuser");
if (in_array($username, $bannedUsernames) && $_SESSION['role'] != 'admin') {
    echo "Username is not allowed";
    return;
}

if (!empty($avatar) && !validateImageURL($avatar)) {
    echo "invalidav";
    return;
}

if (!empty($background) && !validateImageURL($background)) {
    echo "invalidbg";
    return;
}

if (strlen($description) > 1024) {
    echo "Description exceeds the maximum length";
    return;
}

$query = "UPDATE `accounts` SET `description`='$description', `username`='$username', `avatar`='$avatar', `background`='$background' WHERE `id`='$user_id'";
$result = $con->query($query);

if ($result) {
    echo "success";
} else {
    echo "unknown error";
}

function validateImageURL($url): bool
{
    $imageInfo = @getimagesize($url);
    return $imageInfo !== false;
}