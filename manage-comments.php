<?php
require_once 'auth/isloggedin.php';
require_once 'auth/isadmin.php';
global $con;
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
    <link rel="stylesheet" href="style/manage-comments.css">
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
    $mngcommentsActive = "class='active'";
    require_once 'components/left-menu.php'; ?>
    <div class="right-pane">
        <?php require_once 'components/top-menu.php'; ?>
        <div class="main flex-column">
            <h1>Manage Comments</h1>
            <div class="browse-section comments-section">
                <?php
                $lastComments = "SELECT `comments`.`id`,`comments`.`video_id`,`comments`.`content`,`comments`.`submit_date`,`accounts`.`id` as `uid`, `accounts`.`username` FROM `comments` INNER JOIN `accounts` ON `comments`.`author_id`=`accounts`.`id` ORDER BY `comments`.`submit_date` DESC LIMIT 24;";
                $result = $con->query($lastComments);

                if ($result->num_rows > 0) {
                    $output = "<table>
                                <tr class='comment-header'>
                                    <th>ID</th>
                                    <th>Episode</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th class='actions-header'>Actions</th>
                                </tr>";
                    while ($commentsRow = $result->fetch_assoc()) {
                        $output .= "<tr class='comment-row' data-comment-id='$commentsRow[id]'>
                                        <td class='last_comment_repId'>{$commentsRow["id"]}</td>
                                        <td class='last_comment_videoId'><a href='watch?v={$commentsRow["video_id"]}'>". htmlspecialchars($commentsRow["video_id"]) . "</a></td>
                                        <td class='last_comment_content'>" . htmlspecialchars($commentsRow["content"]) . "</td>
                                        <td class='last_comment_date'>" . htmlspecialchars($commentsRow["submit_date"]) . "</td>
                                        <td class='last_comment_author'><a href='profile?u={$commentsRow["uid"]}'>" . htmlspecialchars($commentsRow["username"]) . "</a></td>
                                        <td class='last_comment_actions'>
                                            <div class='actions flex v-mid'>
                                                <a href='watch?v={$commentsRow["video_id"]}#comments'><span class='fas fa-external-link'></span></a>
                                                <a href='scripts/manage-comments-actions?action=delete-comment&id={$commentsRow["id"]}'><span class='fas fa-comment-alt-times'></span></a>
                                                <a href='scripts/manage-comments-actions?action=ban&u={$commentsRow["uid"]}&reason=komentarz'><span class='fas fa-user-lock'></span></a>
                                            </div>
                                        </td>
                                    </tr>";
                    }
                    $output .= "</table>";
                } else {
                    $output = "<p class='no-comments'>No comments found.</p>";
                }
                echo $output;
                ?>
            </div>
        </div>
    </div>
</div>
<script>
</script>
</body>

</html>
<?php $con->close() ?>