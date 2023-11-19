<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
        <link rel="stylesheet" href="style/top.css">
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
        $topActive = "class='active'";
        require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-column">
                <h1>Top charts</h1>
                <div class="top-section">
                    <div class="top-block top-views">
                        <h3>Most views</h3>
                        <div class="episodes">
                        <?php
                        $topViews_query = "SELECT `series_id`, SUM(`views`) AS total_views, `series`.`alt_title`
                                            FROM `episodes`
                                            INNER JOIN `series` ON `series`.`id` = `episodes`.`series_id`
                                            GROUP BY `series_id`
                                            ORDER BY total_views DESC
                                            LIMIT 10;";
                        if ($stmt = $con->prepare($topViews_query)) {
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                echo "<div class='episodes'>";
                                $i = 0;
                                while ($res = $result->fetch_assoc()) {
                                    ++$i;
                                    echo "<a href='series?s={$res['series_id']}'><div class='episode'><div class='epNum'>{$i}</div><div class='epTitle'>{$res['alt_title']}</div></div></a>";
                                }
                                echo "</div>";
                            } else {
                                echo "<p class='no-episodes'>No series<p>";
                            }

                            $stmt->close();
                        } else {
                            echo "<p class='no-episodes'>Database error. Try again later.<p>";
                        }
                        ?>
                        </div>
                    </div>
                    <div class="top-block top-new">
                        <h3>Best among the new</h3>
                        <div class="episodes">
                            <?php
                            $bestNew_query = "SELECT `series_id`, SUM(`views`) AS total_views, `series`.`alt_title`
                                                FROM `episodes`
                                                INNER JOIN `series` ON `series`.`id` = `episodes`.`series_id`
                                                WHERE `series`.`added_date` >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
                                                GROUP BY `series_id`
                                                ORDER BY total_views DESC
                                                LIMIT 10;";
                            if ($stmt = $con->prepare($bestNew_query)) {
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    echo "<div class='episodes'>";
                                    $i = 0;
                                    while ($res = $result->fetch_assoc()) {
                                        ++$i;
                                        echo "<a href='series?s={$res['series_id']}'><div class='episode'><div class='epNum'>{$i}</div><div class='epTitle'>{$res['alt_title']}</div></div></a>";
                                    }
                                    echo "</div>";
                                } else {
                                    echo "<p class='no-episodes'>No series<p>";
                                }

                                $stmt->close();
                            } else {
                                echo "<p class='no-episodes'>Database error. Try again later.<p>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="top-block top-prev-year">
                        <h3>Best in <?=date('Y');?></h3>
                        <div class="episodes">
                            <?php
                            $bestOfYear_query = "SELECT `series_id`, SUM(`views`) AS total_views, `series`.`alt_title`
                                                FROM `episodes`
                                                INNER JOIN `series` ON `series`.`id` = `episodes`.`series_id`
                                                WHERE YEAR(`series`.`added_date`) = 2023
                                                GROUP BY `series_id`
                                                ORDER BY total_views DESC
                                                LIMIT 10;";
                            if ($stmt = $con->prepare($bestOfYear_query)) {
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    echo "<div class='episodes'>";
                                    $i = 0;
                                    while ($res = $result->fetch_assoc()) {
                                        ++$i;
                                        echo "<a href='series?s={$res['series_id']}'><div class='episode'><div class='epNum'>{$i}</div><div class='epTitle'>{$res['alt_title']}</div></div></a>";
                                    }
                                    echo "</div>";
                                } else {
                                    echo "<p class='no-episodes'>No series<p>";
                                }

                                $stmt->close();
                            } else {
                                echo "<p class='no-episodes'>Database error. Try again later.<p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
    </div>
    </body>

    </html>
<?php $con->close() ?>