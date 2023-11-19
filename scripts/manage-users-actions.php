<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/isloggedin.php';
require_once '../auth/isadmin.php';
require_once '../auth/db_con.php';
if (!empty($_GET["action"])) {
    if (!empty($_GET['u']) && !empty($_GET['reason']) && $_GET['action'] == "ban") {
        $getUserData_query = "SELECT * FROM `accounts` WHERE `id` = '$_GET[u]'";
        $result = $con->query($getUserData_query)->fetch_assoc();
        if ($result['role'] != "user") {
            header('Location: ../manage-users?e=cantbanadm');
            exit;
        }
        $ban_query = "INSERT INTO `banned`(`id`, `username`, `password`, `email`, `role`, `description`, `avatar`, `reg_date`, `reason`) VALUES ('$result[id]', '$result[username]', '$result[password]', '$result[email]', '$result[role]', '$result[description]', '$result[avatar]', '$result[reg_date]', '$_GET[reason]')";
        $removeUser_query = "DELETE FROM `accounts` WHERE `id` = '$_GET[u]'";
        if ($con->query($ban_query) && $con->query($removeUser_query)) {
            header('Location: ../manage-users?s=banned');
            exit;
        }
    }
    if (!empty($_GET['u']) && $_GET['action'] == "unban") {
        $getUserData_query = "SELECT * FROM `banned` WHERE `id` = '$_GET[u]'";
        $result = $con->query($getUserData_query)->fetch_assoc();
        $unban_query = "INSERT INTO `accounts`(`id`, `username`, `password`, `email`, `role`, `description`, `avatar`, `reg_date`) VALUES ('$result[id]', '$result[username]', '$result[password]', '$result[email]', '$result[role]', '$result[description]', '$result[avatar]', '$result[reg_date]')";
        $addUser_query = "DELETE FROM `banned` WHERE `id` = '$_GET[u]'";
        if ($con->query($unban_query) && $con->query($addUser_query)) {
            header('Location: ../manage-users?s=unbanned');
            exit;
        }
    }
    if (!empty($_GET['u']) && $_GET['action'] == "reset") {
        $resetPassword_query = "UPDATE `accounts` SET `resetPassword`=1 WHERE  `id` = '$_GET[u]'";
        if ($con->query($resetPassword_query)) {
            header('Location: ../manage-users?s=sresetrequest');
            exit;
        }
    }
    if (!empty($_GET['u']) && $_GET['action'] == "edit") {
        header("Location: ../edit-profile?u=$_GET[u]");
        exit;
    }
    if (!empty($_GET['u']) && $_GET['action'] == "delete") {
        if ($_SESSION['role'] != "admin") {
            header('Location: ../manage-users?e=unauthorized');
            exit;
        }
        $deleteAccount_query = "DELETE FROM `accounts` WHERE `id` = '$_GET[u]'";
        if ($con->query($deleteAccount_query)) {
            header('Location: ../manage-users?s=sdeluser');
            exit;
        }
    }
}
