<?php
if (!defined('ptw-module')) {
    header("location: /browse?e=unauthorized");
    exit;
}
require_once 'auth/isloggedin.php';
require_once 'auth/db_con.php';
$query = $con->prepare('SELECT * FROM `plan_to_watch` WHERE `plan_to_watch`.`series_id` = ? AND `plan_to_watch`.`user_id` = ?');
$query->bind_param('ii', $series_id, $_SESSION['id']);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    echo "<div class='addtowatch'><span class='fas fa-bookmark series-added ptw-button' data-series-id='$series_id'></span></div>";
} else {
    echo "<div class='addtowatch'><span class='fas fa-plus series-toadd ptw-button' data-series-id='$series_id'></span></div>";
}
