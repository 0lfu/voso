<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'auth/db_con.php';
$query = "SELECT `id` FROM `series`";
$stmt = $con->prepare($query);

if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($seriesId);
    $seriesIds = [];
    while ($stmt->fetch()) {
        $seriesIds[] = $seriesId;
    }
    if (!empty($seriesIds)) {
        $randomSeriesId = $seriesIds[array_rand($seriesIds)];
        header("Location: /series?s=$randomSeriesId");
        exit;
    } else {
        header("Location: /browse?e=error");
        exit;
    }
} else {
    header("Location: /browse?e=error");
    exit;
}