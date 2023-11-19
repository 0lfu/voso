<?php
require_once 'auth/isloggedin.php';
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
        <link rel="stylesheet" href="style/watchlist.css">
        <!-- JQUERY -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <!-- NOTY.JS -->
        <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
        <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
        <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
        <script type="text/javascript" src="scripts/notifications.js"></script>
        <!-- TITLE AND FAVICON -->
        <link rel="icon" type="image/png" href="logo/favicon.png"/>
        <title>Voso</title>
    </head>

    <body>
    <div class="wrapper">
        <?php
        $watchlistActive = "class='active'";
        require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-column">
                <h1>Watch list</h1>
                <div class="ptw-section">
                    <?php
                    $query1 = "SELECT * FROM `series` INNER JOIN `plan_to_watch` ON `plan_to_watch`.`series_id` = `series`.`id` WHERE `plan_to_watch`.`user_id` = ?";
                    $stmt1 = $con->prepare($query1);
                    $stmt1->bind_param("i", $_SESSION['id']);
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();
                    if ($result1->num_rows === 0) {
                        echo "<div class='no-watchlist'>No series added to watch list.</div>";
                    } else {
                        while ($row1 = $result1->fetch_assoc()) {
                            $brdType = strtolower($row1['brd-type']);
                            if ($brdType === 'series') {
                                $badgeText = "Season {$row1['season']}";
                            } elseif ($brdType === 'movie') {
                                $badgeText = "Movie";
                            }
                            echo "<a href='series?s=$row1[id]'>
                                    <div class='ptw-item'>
                                        <img src='$row1[poster]'>
                                        <div class='ptw-data'>
                                            <p>$row1[title]</p>
                                            <span class='badge'>$badgeText</span>
                                        </div>
                                    </div>
                                  </a>";
                        }
                    }
                    $stmt1->close();
                    ?>
                </div>
            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
    </div>
    </body>

    </html>
<?php $con->close() ?>