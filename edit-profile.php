<?php
require_once 'auth/isloggedin.php';
require_once 'auth/db_con.php';
if (isset($_SESSION['role'])) {
    echo '<script>var userRole = "' . $_SESSION['role'] . '";</script>';
}
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
    <link rel="stylesheet" href="style/edit-profile.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!-- CHAR COUNT -->
    <script src="scripts/charcount.js" type="text/javascript"></script>
    <!-- VALIDATION -->
    <?php
    require_once 'scripts/validate-profileEdit.php'
    ?>
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
                <div class="edit-profile-wrapper">
                    <div class="user-card">
                        <div class="background">
                            <?php
                            if (empty($_GET['u']) || $_GET['u'] == $_SESSION['id']) {
                                $id = $_SESSION['id'];
                                $getUserData_query = "SELECT * FROM `accounts` WHERE `id` = ?";
                                $stmt = $con->prepare($getUserData_query);
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $res = $result->fetch_assoc();
                            } else if ($_GET['u'] != $_SESSION['id'] && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "mod")) {
                                $id = $_GET['u'];
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
                            } else {
                                echo '<script>window.location.href = "edit-profile?e=unauthorized";</script>';
                                exit;
                            }                                                        

                            $background = "";
                            if (!empty($res['background']) && $res['background'] !== null) {
                                $imageInfo = @getimagesize($res['background']);
                                if ($imageInfo !== false) {
                                    $background = "<img class='backgroundimg' src='" . $res['background'] . "' alt='User Background' draggable='false'>";
                                }
                            }
                            echo $background;
                            ?>
                        </div>
                        <div class="avatar-nickname">
                        <?php                            
                            $avatar = "<img class='avatar-edit' src='resources/default.jpg' alt='User Avatar'>";
                            if (!empty($res['avatar']) && $res['avatar'] !== null) {
                                $imageInfo = @getimagesize($res['avatar']);
                                if ($imageInfo !== false) {
                                    $avatar = '<img class="avatar-edit" src="' . $res['avatar'] . '" alt="User Avatar">';
                                }
                            }
                            echo $avatar;
                            $nickname = htmlspecialchars($res['username']);
                            echo "<input class='nickname-edit' id='nickname-edit' value='$nickname' placeholder='Edit username'>";
                        ?>
                        </div>
                        <div class="avatar-background-urls">
                            <?php
                                echo "<div class='input-container'><p>Avatar URL</p><input class='avatar-url' id='avatar-url' value='" . ($res['avatar'] ?? "") . "' placeholder='Avatar URL'></div>";
                                echo "<div class='input-container'><p>Background URL</p><input class='background-url' id='background-url' value='" . ($res['background'] ?? "") . "' placeholder='Background URL'></div>";
                            ?>
                        </div>
                        <div class="description">
                        <?php
                            echo <<< DESC
                            <div class='input-container'>
                                <p>Description</p>
                                <textarea maxlength="1024" id="desc-area" class="description-area">$res[description]</textarea>
                               <span class="charCountElement"><span id="charCount">0</span>/1024</span>
                            </div>
                            DESC;
                        ?>
                        </div>
                    </div>
                    <button type="submit" id="save-changes">Save</button>
                </div>
            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
    </div>
</body>

</html>
<?php $con->close() ?>