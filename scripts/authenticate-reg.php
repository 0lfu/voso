<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register_form_submit'])) {
    header("Location: /browse?e=unauthorized");
    exit;
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/db_con.php';

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    header('Location: ../register?e=error');
    exit;
}

if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    header('Location: ../register?e=missingdata');
    exit;
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    header('Location: ../register?e=invalidemail');
    exit;
}
if (preg_match('/^[a-zA-Z0-9_-]+$/', $_POST['username']) == 0) {
    header('Location: ../register?e=invalidusername');
    exit;
}
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 6) {
    header('Location: ../register?e=passwordwrnglength');
    exit;
}
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    $uniqid = uniqid();
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        header('Location: ../register?e=usernameexist');
        exit;
    } else {
        if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE email = ?')) {
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                header('Location: ../register?e=emailexist');
                exit;
            }
        }
        if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)')) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
            $stmt->execute();
            header('Location: ../login?s=sreg');
        } else {
            header('Location: ../register?e=error');
            exit;
        }
    }
    $stmt->close();
} else {
    header('Location: ../register?e=error');
    exit;
}
$con->close();
