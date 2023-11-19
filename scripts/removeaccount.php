<?php
require_once '../auth/isloggedin.php';
require_once '../auth/db_con.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirmationText = strtolower($_POST['confirm']);

    if ($confirmationText !== "confirm") {
        header("Location: ../profile?e=wrongconfirmtext");
        exit;
    } else {
        $userId = $_SESSION['id'];
        $query = "DELETE FROM `accounts` WHERE `id` = $userId";
        $result = mysqli_query($con, $query);
        if ($result) {
            session_destroy();
            header("Location: ../browse");
        } else {
            header("Location: ../profile?e=dberr");
        }
        exit;
    }
} else {
    header("location: /browse?e=unauthorized");
    exit;
}
