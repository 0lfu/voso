<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login_form_submit'])) {
    header("Location: /browse?e=unauthorized");
    exit();
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/db_con.php';

if (!isset($_POST['username'], $_POST['password'])) {
    header('Location: ../login?e=error');
    exit;
}
if ($stmt = $con->prepare('SELECT `id`, `password`,`email`, `role` FROM `accounts` WHERE `email` = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $password, $email, $role);
        $stmt->fetch();
        if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            $_SESSION['role'] = $role;
            if (isset($_POST['remember']) && $_POST['remember'] === "on") {
                $params = session_get_cookie_params();
                setcookie(session_name(), session_id(), time() + 60 * 60 * 24 * 7, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
            }
            header('Location: ../browse');
        } else {
            header('Location: ../login?e=wrngpass');
        }
    } else {
        header('Location: ../login?e=wrngpass');
    }
    $stmt->close();
}
