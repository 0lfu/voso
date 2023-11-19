<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/isloggedin.php';
require_once '../auth/db_con.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current'];
    $newPassword = $_POST['new'];
    $renewPassword = $_POST['renew'];

    if ($newPassword !== $renewPassword) {
        header("Location: ../profile?e=newpassnotmatch");
        exit;
    }

    $userId = $_SESSION['id'];
    $query = "SELECT `password` FROM `accounts` WHERE `id` = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        if (!password_verify($currentPassword, $storedPassword)) {
            header("Location: ../profile?e=wrongcurpass");
            exit;
        }
    } else {
        header("Location: ../profile?e=dberr");
        exit;
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateQuery = "UPDATE accounts SET `password` = ? WHERE `id` = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("si", $hashedPassword, $userId);

    if ($stmt->execute()) {
        header("Location: ../profile?s=chngdpsswd");
    } else {
        header("Location: ../profile?e=dberr");
    }
    exit;
} else {
    header("Location: ../profile?e=error");
    exit;
}
