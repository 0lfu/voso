<?php
require_once 'auth/isloggedin.php';
require_once 'auth/isadmin.php';
require_once 'auth/db_con.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Voso">
    <meta name="description" content="Video streaming service">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <!-- STYLES -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://vjs.zencdn.net/7.21.1/video-js.css">
    <link rel="stylesheet" href="style/manage-users.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Voso</title>
</head>

<body>
    <div class="wrapper">
        <?php
        $mngusersActive = "class='active'";
        require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-column">
                <div class="users-section">
                    <h1>Manage users</h1>
                    <?php
                    $usersData_query = "SELECT `id`, `username`, `email`, `role` FROM `accounts` ORDER BY `id`";
                    $result = $con->query($usersData_query);

                    if ($result->num_rows > 0) {
                        $output = "<table>
                                    <tr class='users-header'>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class='actions-header'>Actions</th>
                                    </tr>";
                        while ($usersRow = $result->fetch_assoc()) {
                            $userId = $usersRow["id"];
                            $output .= "<tr class='users-row' data-users-id='$userId'>
                                            <td class='users_id'>{$usersRow["id"]}</td>
                                            <td class='users_username'><a href='profile?u={$usersRow["id"]}'>{$usersRow["username"]}</a></td>
                                            <td class='users_email'>{$usersRow["email"]}</td>
                                            <td class='users_role'>{$usersRow["role"]}</td>
                                            <td class='users_actions'>
                                                <div class='actions flex v-mid'>
                                                    <a href='scripts/manage-users-actions?u={$userId}&action=reset'><span class='fas fa-unlock'></span></a>
                                                    <a href='scripts/manage-users-actions?u={$userId}&action=edit'><span class='fas fa-user-edit'></span></a>
                                                    <a href='scripts/manage-users-actions?u={$userId}&action=ban&reason=panel'><span class='fas fa-user-lock'></span></a>
                                                    <a href='scripts/manage-users-actions?u={$userId}&action=delete'><span class='fas fa-trash'></span></a>
                                                </div>
                                            </td>
                                        </tr>";
                        }
                        $output .= "</table>";
                    } else {
                        $output = "No users found.";
                    }
                    echo $output;
                    ?>
                    <h2>Banned</h2>
                    <?php
                    $usersData_query = "SELECT `id`, `username`, `email`, `role`, `reason` FROM `banned` ORDER BY `id`";
                    $result = $con->query($usersData_query);

                    if ($result->num_rows > 0) {
                        $output = "<table>
                                    <tr class='users-header'>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Reason</th>
                                        <th class='actions-header'>Actions</th>
                                    </tr>";
                        while ($usersRow = $result->fetch_assoc()) {
                            $userId = $usersRow["id"];
                            $output .= "<tr class='users-row' data-users-id='$userId'>
                                            <td class='users_id'>{$usersRow["id"]}</td>
                                            <td class='users_username'>{$usersRow["username"]}</td>
                                            <td class='users_email'>{$usersRow["email"]}</td>
                                            <td class='users_role'>{$usersRow["role"]}</td>
                                            <td class='users_reason'>{$usersRow["reason"]}</td>
                                            <td class='users_actions'>
                                                <div class='actions flex v-mid'>
                                                    <a href='scripts/manage-users-actions?u={$userId}&action=unban'><span class='fas fa-user-unlock'></span></a>
                                                </div>
                                            </td>
                                        </tr>";
                        }
                        $output .= "</table>";
                    } else {
                        $output = "<div class='no-banned'>No users were banned.</div>";
                    }
                    echo $output;
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php $con->close() ?>