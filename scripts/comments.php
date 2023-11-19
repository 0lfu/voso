<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db_con.php';
function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array('y' => 'year', 'm' => 'months', 'w' => 'weeks', 'd' => 'days', 'h' => 'hours', 'i' => 'minutes', 's' => 'seconds');
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v;
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'now';
}
function show_comments($con, $comments, $parent_id = -1)
{
    $html = '';
    if (empty($comments)) {
        $html .= "<p class='no-comments'>No comments</p>";
    } else {
        if ($parent_id != -1) {
            array_multisort(array_column($comments, 'submit_date'), SORT_ASC, $comments);
        }
        foreach ($comments as $comment) {
            $getUsername_query = "SELECT `username`, `avatar` FROM `accounts` WHERE `id` = ?";
            $stmt = $con->prepare($getUsername_query);
            $stmt->bind_param("i", $comment['author_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $res = $result->fetch_assoc();
            $stmt->close();
            $avatar = "<img src='resources/default.jpg' width='20vw' style='border-radius:20%; margin-right: 1%;'>";
            if (!empty($res['avatar']) && $res['avatar'] !== null) {
                $imageInfo = @getimagesize($res['avatar']);
                if ($imageInfo !== false) {
                    $avatar = "<img src='$res[avatar]' width='20vw' style='border-radius:20%; margin-right: 1%;'>";
                }
            }

            $adminButtons = '';
            $userButtons = '';
            if(isset($_SESSION['loggedin']))
            {
                $userButtons = '
                <a class="reply_comment_btn" href="#" data-comment-id="' . $comment['id'] . '">Reply</a>
                <a class="report_comment_btn" href="#" data-comment-id="' . $comment['id'] . '">Report</a>';
                if ($_SESSION['role'] == "admin" || $_SESSION['role'] == "mod") {
                    $adminButtons = "<a class='delete_comment_btn' href='scripts/manage-comment-actions.php?action=delete-comment&id=$comment[id]'>Delete</a>";
                }
            }
            if ($comment['parent_id'] == $parent_id) {
                $html .= '
                <div class="comment" id="' . $comment['id'] . '">
                    <div class="flex flex-row v-mid">'
                    . $avatar .
                    '<a href="profile?u=' . $comment['author_id'] . '"><h3 class="name">' . htmlspecialchars($res['username'], ENT_QUOTES) . '</h3></a>
                        <span class="date">' . time_elapsed_string($comment['submit_date']) . '</span>
                    </div>
                    <p class="content">' . nl2br(htmlspecialchars($comment['content'], ENT_QUOTES)) . '</p>' . $userButtons . $adminButtons . show_write_comment_form($con, $comment['id']) . '
                    <div class="replies">
                    ' . show_comments($con, $comments, $comment['id']) . '
                    </div>
                </div>
                ';
            }
        }
    }
    return $html;
}
function show_write_comment_form($con, $parent_id = -1)
{
    return '
    <div class="write_comment" data-comment-id="' . $parent_id . '">
        <form class="resp">
            <input name="parent_id" type="hidden" value="' . $parent_id . '">
            <input type="text" name="content" placeholder="Write replay..." required>
        </form>
    </div>
    ';
}
if (isset($_GET['video_id'])) {
    if (isset($_SESSION['id'], $_POST['content'])) {
        $insertComment_query = "INSERT INTO comments (video_id, parent_id, author_id, content, submit_date) VALUES (?, NULL, ?, ?, NOW())";
        if (!empty($_POST['parent_id']) && $_POST['parent_id'] > '0') {
            $insertComment_query = "INSERT INTO comments (video_id, parent_id, author_id, content, submit_date) VALUES (?, ?, ?, ?, NOW())";
        }
        $stmt = $con->prepare($insertComment_query);
        if (!empty($_POST['parent_id']) && $_POST['parent_id'] > '0') {
            $stmt->bind_param("iiis", $_GET['video_id'], $_POST['parent_id'], $_SESSION['id'], $_POST['content']);
        } else {
            $stmt->bind_param("iis", $_GET['video_id'], $_SESSION['id'], $_POST['content']);
        }

        if ($stmt->execute()) {
            $stmt->close();
            $con->close();
            exit();
        } else {
            $stmt->close();
            $con->close();
            exit();
        }
    }
    $getAllComments_query = "SELECT * FROM comments WHERE video_id = ? ORDER BY submit_date DESC";
    $stmt = $con->prepare($getAllComments_query);
    $stmt->bind_param("i", $_GET['video_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
    foreach ($comments as &$comment) {
        if ($comment['parent_id'] === null) {
            $comment['parent_id'] = -1;
        }
    }
} else {
    $con->close();
    header('location: /browse');
    exit;
}
?>
<div class="comment_header">
    <div class="write_comment first-comment" data-comment-id="-1">
        <form>
            <input name="parent_id" type="hidden" value="-1">
            <?php
                if(!isset($_SESSION['loggedin'])){
                    echo '<input type="text" name="content" placeholder="Log in to post a comment..." disabled>';
                }
                else{
                    echo '<input type="text" name="content" placeholder="Write a comment..." required>';
                }
            ?>
        </form>
    </div>
</div>

<?= show_write_comment_form($con) ?>

<?= show_comments($con, $comments) ?>
<?php $con->close() ?>