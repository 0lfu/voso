<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/isloggedin.php';
require_once '../auth/isadmin.php';
require_once '../auth/db_con.php';
if (!empty($_GET["s"])) {
    if (!empty($_GET['action']) && $_GET['action'] == "hide") {
        $hideAllEpisodes_query = "UPDATE `episodes` SET `isActive`='0' WHERE `series_id` = '$_GET[s]'";
        $hide_query = "UPDATE `series` SET `isActive`='0' WHERE `id` = '$_GET[s]'";
        if ($con->query($hide_query) && $con->query($hideAllEpisodes_query)) {
            header('Location: ../manage-content?s=shidden');
            exit;
        }
    }
    if (!empty($_GET['action']) && $_GET['action'] == "show") {
        $showAllEpisodes_query = "UPDATE `episodes` SET `isActive`='1' WHERE `series_id` = '$_GET[s]'";
        $show_query = "UPDATE `series` SET `isActive`='1' WHERE `id` = '$_GET[s]'";
        if ($con->query($show_query) && $con->query($showAllEpisodes_query)) {
            header('Location: ../manage-content?s=sshown');
            exit;
        }
    }
    if (!empty($_GET['action']) && $_GET['action'] == "edit") {
        header('Location: ../edit-content?s=' . $_GET["s"]);
        exit;
    }
    if (!empty($_GET['action']) && $_GET['action'] == "delete") {
        if ($_SESSION['role'] != "admin") {
            header('Location: ../manage-content?e=unauthorized');
            exit;
        }
        $delete_query = "DELETE FROM `series` WHERE `id` = '$_GET[s]'";
        if ($con->query($delete_query)) {
            header('Location: ../manage-content?s=sdeleted');
            exit;
        }
    }
}
if (!empty($_GET["e"])) {
    if (!empty($_GET['action']) && $_GET['action'] == "hide") {
        $hide_query = "UPDATE `episodes` SET `isActive`='0' WHERE `id` = '$_GET[e]'";
        if ($con->query($hide_query)) {
            header('Location: ../manage-content?s=ehidden');
            exit;
        }
    }
    if (!empty($_GET['action']) && $_GET['action'] == "show") {
        $show_query = "UPDATE `episodes` SET `isActive`='1' WHERE `id` = '$_GET[e]'";
        if ($con->query($show_query)) {
            header('Location: ../manage-content?s=eshown');
            exit;
        }
    }
    if (!empty($_GET['action']) && $_GET['action'] == "edit") {
        header('Location: ../edit-content?e=' . $_GET["e"]);
        exit;
    }
    if (!empty($_GET['action']) && $_GET['action'] == "delete") {
        if ($_SESSION['role'] != "admin") {
            header('Location: ../manage-content?e=unauthorized');
            exit;
        }
        $delete_query = "DELETE FROM `episodes` WHERE `id` = '$_GET[e]'";
        if ($con->query($delete_query)) {
            header('Location: ../manage-content?s=edeleted');
            exit;
        }
    }
}
