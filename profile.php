<?php
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
    <link rel="stylesheet" href="style/profile.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!-- FORM VALIDATOR -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!--  ACTION HANDLER  -->
    <script src="scripts/profile-actionHandler.js" type="text/javascript"></script>
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Voso</title>
</head>

<body>
    <div class="wrapper">
        <?php require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main">
                <div class="profile-wrapper">
                    <div class="user-card">
                        <div class="background">
                        <?php
                            if (!isset($_SESSION['id']) && empty($_GET['u'])) {
                                echo '<script>window.location.href = "browse";</script>';
                                exit;
                            }
                            
                            if (empty($_GET['u']) || $_GET['u'] == $_SESSION['id']) {
                                $id = $_SESSION['id'];
                            } else {
                                $id = $_GET['u'];
                            }
                            $getUserData_query = "SELECT * FROM `accounts` WHERE `id` = ?";
                            $stmt = $con->prepare($getUserData_query);
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows == 0) {
                                echo '<script>window.location.href = "profile?e=usernotexist";</script>';
                                exit;
                            }
                            $res = $result->fetch_assoc();

                            $background = "";
                            if (!empty($res['background']) && $res['background'] !== null) {
                                $imageInfo = @getimagesize($res['background']);
                                if ($imageInfo !== false) {
                                    $background = "<img class='backgroundimg' src='" . htmlspecialchars($res['background'], ENT_QUOTES) . "' alt='User Background' draggable='false'>";
                                }
                            }
                            echo $background;
                        ?>
                        </div>
                        <div class="avatar-nickname">
                        <?php                                                  
                            $avatar = "<img class='avatar' src='resources/default.jpg' alt='User Avatar' draggable='false'>";
                            if (!empty($res['avatar']) && $res['avatar'] !== null) {
                                $imageInfo = @getimagesize($res['avatar']);
                                if ($imageInfo !== false) {
                                    $avatar = "<img class='avatar' src='" . $res['avatar'] . "' alt='User Avatar' draggable='false'>";
                                }
                            }
                            
                            echo $avatar;
                            $nickname = htmlspecialchars($res['username']);
                            echo "<p class='nickname'>$nickname</p>";
                            
                            
                        ?>
                        </div>
                        <div class="description">
                        <?php
                            $description = nl2br(htmlspecialchars($res['description']));
                            if (empty(trim($description))) {
                                $description = "No description.";
                            }
                            echo $description;
                        ?>
                        </div>
                    </div>
                    <?php
                    if (empty($_GET['u']) || $_GET['u'] == $_SESSION['id']) {
                        $stmt = $con->prepare("SELECT COUNT(*) as `ilosc_kom` FROM `comments` WHERE `author_id` = ?");
                        $stmt->bind_param("i", $res['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $comments_count = $row['ilosc_kom'];
                        
                        $stmt = $con->prepare("SELECT COUNT(*) as `ilosc_like` FROM `likes` WHERE `user_id` = ?");
                        $stmt->bind_param("i", $res['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $likes_count = $row['ilosc_like'];
                        
                        $stmt = $con->prepare("SELECT `alt_title`, `likes`, `episode_count`, (`likes`/`episode_count`) AS `likes_per_episode`
                                            FROM (
                                            SELECT `series`.`alt_title`, count(`likes`.`user_id`) AS `likes`, 
                                            (SELECT count(`id`) FROM `episodes` WHERE `series_id` = `series`.`id`) AS `episode_count`
                                            FROM `likes` 
                                            INNER JOIN `episodes` on `episodes`.`id` = `likes`.`video_id`
                                            INNER JOIN `series` on `series`.`id` = `episodes`.`series_id`
                                            WHERE `likes`.`user_id` = ?
                                            GROUP BY `series`.`id`, `series`.`alt_title`
                                            ) subquery
                                            ORDER BY `likes_per_episode` DESC
                                            LIMIT 1");
                        $stmt->bind_param("i", $res['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        if ($result && isset($row['alt_title'])) {
                            $favourite_series = htmlspecialchars($row['alt_title'], ENT_QUOTES);
                        } else {
                            $favourite_series = "None.";
                        }                        
                        
                        $role = 'user';
                        if ($res['role'] == "mod") {
                            $role = 'moderator';
                        }
                        if ($res['role'] == "admin") {
                            $role = 'administrator';
                        }
                        
                        $stmt = $con->prepare("SELECT `id`, `reg_date`, DATEDIFF(CURDATE(), `reg_date`) AS `days_since_registration` FROM `accounts` WHERE `id` = ?");
                        $stmt->bind_param("i", $res['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $days_from_register = $row['days_since_registration'];
                        
                        echo <<< USERINFO
                        <div class="user-info">
                            <div class="info">
                                <h2>Your data:</h2>
                                <p>Username: <span class="stats-data">$nickname</span></p>
                                <p>Email: <span class="stats-data">$res[email]</span></p>
                                <p>Password: <span class="stats-data">********</span></p>
                                <p>Role: <span class="stats-data">$role</span></p>
                                <p>ID: <span class="stats-data">$res[id]</span></p>
                            </div>
                            <div class="stats">
                                <h2>Statistics:</h2>
                                <p>Comments: <span class="stats-data">$comments_count</span></p>
                                <p>Likes: <span class="stats-data">$likes_count</span></p>
                                <p>Favourite serie: <span class="stats-data">$favourite_series</span></p>
                                <p>Joined: <span class="stats-data">$res[reg_date]</span></p>
                                <p>Days untill join: <span class="stats-data">$days_from_register</span></p>
                            </div>
                            <div class="settings">
                                <h2>Manage your account:</h2>
                                <div class="settings-wrapper">
                                    <a class="option" href="edit-profile">Edit Profile</a>
                        USERINFO;
                        if(empty($res['facebook_id']) && empty($res['google_id'])){
                            echo '<a class="option changepassword" href="javascript: void();">Change Password</a>';
                        }
                        echo <<< USERINFO
                                    <a class="option removeaccount" href="javascript: void();">Remove Account</a>
                                </div>   
                            </div>
                        </div>
                        USERINFO;
                    }
                    ?>
                </div>
            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
    </div>
    <?php
        if(empty($res['facebook_id']) && empty($res['google_id'])){
            echo <<< CHNGPASFORM
            <div class="form-popup-bg chngpass-form">
            <div class="form-container">
                <span id="btnCloseForm" class="fas fa-times"></span>
                <h1>Change password</h1>
                <form id="changepas-form" method="POST" action="scripts/changepassword">
                    <div class="form-group">
                        <label class="form-group-title">Current password</label>
                        <input type="password" class="form-control" name="current">
                    </div>
                    <div class="form-group">
                        <label class="form-group-title">New password:</label>
                        <input type="password" class="form-control new" name="new">
                    </div>
                    <div class="form-group">
                        <label class="form-group-title">Repeat new password:</label>
                        <input type="password" class="form-control renew" name="renew">
                    </div>
                    <button type="submit">Change password</button>
                </form>
                <script src="scripts/changepas-validate.js"></script>
            </div>
        </div>
        CHNGPASFORM;
        }
    ?>
    <div class="form-popup-bg rmvacc-form">
        <div class="form-container">
            <span id="btnCloseForm" class="fas fa-times"></span>
            <h1>Delete account</h1>
            <form id="rmvacc-form" method="POST" action="scripts/removeaccount">
                <div class="form-group">
                    <label class="form-group-title">Do you confirm the deletion of your account? If yes, please type <span style='color: var(--accent1)'>CONFIRM</span>.</label>
                    <input type="text" class="form-control" name="confirm">
                </div>
                <button type="submit">Remove account forever</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php $con->close() ?>