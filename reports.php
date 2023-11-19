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
    <link rel="stylesheet" href="style/reports.css">
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
        $reportsActive = "class='active'";
        require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-column">
                <h1>Reported episodes</h1>
                <div class="browse-section episodes-section">
                    <?php
                    $reportedPlayerData_query = "SELECT `player_reports`.`video_id`,`player_reports`.`reported_by`, `accounts`.`username`, `episodes`.`title`
                    FROM `player_reports`
                    LEFT JOIN `accounts` ON `player_reports`.`reported_by` = `accounts`.`id`
                    INNER JOIN `episodes` ON `episodes`.`id` = `player_reports`.`video_id`";
                    $result = $con->query($reportedPlayerData_query);

                    if ($result->num_rows > 0) {
                        $output = "<table>
                                    <tr class='player-header'>
                                        <th>Episode ID</th>
                                        <th>Title</th>
                                        <th>Reported by</th>
                                        <th class='actions-header'>Actions</th>
                                    </tr>";
                        while ($playerRow = $result->fetch_assoc()) {
                            $reported_by = "Not logged in";
                            if($playerRow["reported_by"] != 0){
                                $reported_by = $playerRow["username"];
                            }
                            $output .= "<tr class='player-row'>
                                            <td class='player_id'><a href='watch?v={$playerRow["video_id"]}'>". htmlspecialchars($playerRow["video_id"]) . "</a></td>
                                            <td class='player_name'><a href='watch?v={$playerRow["video_id"]}'>". htmlspecialchars($playerRow["title"]) . "</a></td>
                                            <td class='player_reportedby'>" . htmlspecialchars($reported_by) . "</td>
                                            <td class='player_actions'>
                                            <div class='actions flex v-mid'>
                                                <a href='watch?v={$playerRow["video_id"]}'><span class='fas fa-external-link'></span></a>
                                                <a href='edit-content?e={$playerRow["video_id"]}'><span class='fas fa-edit'></span></a>
                                                <a href='scripts/manage-reports-actions?action=delete-player-report&id={$playerRow["video_id"]}'><span class='fas fa-trash'></span></a>
                                            </div>
                                            </td>
                                        </tr>";
                        }
                        $output .= "</table>";
                    } else {
                        $output = "<p class='no-reports'>No reports were found.</p>";
                    }
                    echo $output;
                    ?>
                </div>
                <h1>Reported comments</h1>
                <div class="browse-section comments-section">
                    <?php
                    $reportedCommentsData_query = "SELECT `comments_reports`.`id`,`comments_reports`.`user_id`,`reported`.`username` as user_username,`comments_reports`.`comment_id`,`comments_reports`.`reason`,`comments_reports`.`note`,`comments_reports`.`reported_by`,`reportedby`.`username` as reported_by_username, `comments`.`content`, `comments`.`video_id`
                    FROM `comments_reports`
                    INNER JOIN `accounts` as `reported` ON `reported`.`id` = `comments_reports`.`user_id`
                    INNER JOIN `comments` ON `comments`.`id` = `comments_reports`.`comment_id`
                    INNER JOIN `accounts` as `reportedby` ON `reportedby`.`id` = `comments_reports`.`reported_by`
                    ORDER BY `comments_reports`.`id`;";
                    $result = $con->query($reportedCommentsData_query);

                    if ($result->num_rows > 0) {
                        $output = "<table>
                                    <tr class='comment-header'>
                                        <th>Comment ID</th>
                                        <th>Reported user</th>
                                        <th>Reason</th>
                                        <th>Comment</th>
                                        <th>Note</th>
                                        <th>Reported by</th>
                                        <th class='actions-header'>Actions</th>
                                    </tr>";
                        while ($commentsRow = $result->fetch_assoc()) {
                            $output .= "<tr class='comment-row' data-comment-id='$commentsRow[comment_id]'>
                                            <td class='comment_repId'>{$commentsRow["id"]}</td>
                                            <td class='comment_reported'><a href='profile?u={$commentsRow["user_id"]}'>". htmlspecialchars($commentsRow["user_username"]) . "</a></td>
                                            <td class='comment_reason'>" . htmlspecialchars($commentsRow["reason"]) . "</td>
                                            <td class='comment_content'>" . htmlspecialchars($commentsRow["content"]) . "</td>
                                            <td class='comment_note'>" . htmlspecialchars($commentsRow["note"]) . "</td>
                                            <td class='comment_reportedBy'><a href='profile?u={$commentsRow["reported_by"]}'>" . htmlspecialchars($commentsRow["reported_by_username"]) . "</a></td>
                                            <td class='comment_actions'>
                                                <div class='actions flex v-mid'>
                                                    <a href='watch?v={$commentsRow["video_id"]}#comments'><span class='fas fa-external-link'></span></a>
                                                    <a href='scripts/manage-reports-actions?action=delete-comment&id={$commentsRow["comment_id"]}'><span class='fas fa-comment-alt-times'></span></a>
                                                    <a href='scripts/manage-reports-actions?action=delete-report&id={$commentsRow["id"]}'><span class='fas fa-trash'></span></a>
                                                    <a href='scripts/manage-reports-actions?action=ban&u={$commentsRow["user_id"]}&reason=komentarz/{$commentsRow["reason"]}'><span class='fas fa-user-lock'></span></a>
                                                </div>
                                            </td>
                                        </tr>";
                        }
                        $output .= "</table>";
                    } else {
                        $output = "<p class='no-reports'>No reports were found.</p>";
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