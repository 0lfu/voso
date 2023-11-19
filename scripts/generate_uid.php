<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/isloggedin.php';
require_once '../auth/isadmin.php';
require_once '../auth/db_con.php';
function generate_unique_id($con)
{
    do {
        $uid = substr(abs(crc32(uniqid())), 0, 9);
        if (strlen($uid) < 9) {
            continue;
        }
        $query = "SELECT * FROM `series` WHERE `id`='$uid'";

        try {
            $result = $con->query($query);
            if ($result === false) {
                throw new Exception("Database query error: " . $con->error);
            }
        } catch (Exception $e) {
            sleep(1);
            continue;
        }

    } while ($result->num_rows > 0 || strlen($uid) != 9);

    return $uid;
}

do {
    $uid = substr(abs(crc32(uniqid())), 0, 9);
    if (strlen($uid) < 9) {
        continue;
    }
    $query = "SELECT * FROM `series` WHERE `id`='$uid'";

    try {
        $result = $con->query($query);
        if ($result === false) {
            throw new Exception("Database query error: " . $con->error);
        }
    } catch (Exception $e) {
        sleep(1);
        continue;
    }

} while ($result->num_rows > 0 || strlen($uid) != 9);

echo $uid;

