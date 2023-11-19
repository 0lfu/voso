<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/isloggedin.php';
require_once '../auth/isadmin.php';
require_once '../auth/db_con.php';
if (!empty($_GET["action"])) {
    if (!empty($_GET['id']) && $_GET['action'] == "delete-comment") {
        $deleteComment_query = "DELETE FROM `comments` WHERE `id` = '$_GET[id]'";
        if ($con->query($deleteComment_query)) {
            $con->close();
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&s=delcom');
            exit;
        }
    }
}
