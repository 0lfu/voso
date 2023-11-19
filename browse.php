<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
global $con;
require_once 'auth/db_con.php';
function generateSeriesSection($query, $sectionTitle, $extraLink = null) {
    global $con;
    if ($result = $con->query($query)) {
        if ($result->num_rows > 0) {
            echo "<h1>";
            if ($extraLink) {
                echo "<a href=\"$extraLink\" class=\"action-link\">$sectionTitle <i class=\"fas fa-chevron-right\"></i></a>";
            } else {
                echo "$sectionTitle";
            }
            echo "</h1>";
            echo '<div class="browse-section">';
            while ($row = $result->fetch_assoc()) {
                $brdType = strtolower($row['brd-type']);
                $badgeText = ($brdType === 'series') ? "Season {$row['season']}" : "Movie";

                echo <<< CONTENT
                    <a href="series?s={$row['id']}" class="browse-item">
                        <img src="{$row['poster']}">
                        <span class="browse-item-numinfo gray">$badgeText</span>
                        <p>{$row['alt_title']}</p>
                    </a>
                CONTENT;
            }
            echo '</div>';
        }
        $result->free();
    }
}
function generateHistorySection() {
    global $con;
    if (isset($_SESSION['loggedin'])) {
        $query = "SELECT * FROM `history` INNER JOIN `accounts` ON `accounts`.`id` = `history`.`id` WHERE `accounts`.`id` = '$_SESSION[id]'";
        $result = $con->query($query);

        if ($result->num_rows > 0) {
            echo "<h1>History</h1><div class='browse-section history'>";
            while ($row = $result->fetch_assoc()) {
                $episodesList = explode(";", $row['last_watched']);
                $historyCount = min(count($episodesList), 5);

                for ($i = 0; $i < $historyCount; $i++) {
                    $query2 = "SELECT `series`.`alt_title`, `series`.`brd-type`, `episodes`.`poster`, `series`.`season`, `episodes`.`ep_number` FROM `episodes` INNER JOIN `series` ON `series`.`id` = `episodes`.`series_id` WHERE `episodes`.`id`='$episodesList[$i]' LIMIT 4";

                    if ($result2 = $con->query($query2)) {
                        while ($row2 = $result2->fetch_assoc()) {
                            $brdType = strtolower($row2['brd-type']);
                            $badgeText = ($brdType === 'series') ? "S{$row2['season']} E{$row2['ep_number']}" : "Movie";

                            echo <<< CONTENT
                                    <a href="watch?v=$episodesList[$i]" class="browse-item episode">
                                        <img src="$row2[poster]">
                                        <span class="browse-item-numinfo">$badgeText</span>
                                        <span class="fas fa-play hover-icon"></span>
                                        <p>$row2[alt_title]</p>
                                    </a>
                            CONTENT;
                        }
                        $result2->free();
                    }
                }
            }
            $result->free();
            echo "</div>";
        }
    }
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
    <link rel="stylesheet" href="style/browse.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- SLICK SLIDER -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="/scripts/slickSettings.js"></script>
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
        $browseActive = "class='active'";
        require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-column">
                <?php
                generateHistorySection();

                $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` ON `episodes`.`series_id` = `series`.`id` WHERE `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 AND COUNT(`episodes`.`id`) < `series`.`ep_count` ORDER BY `series`.`added_date` DESC LIMIT 16;";
                generateSeriesSection($query, 'Airing');

                $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags`, SUM(`episodes`.`views`) AS total_views FROM `series` INNER JOIN `episodes` ON `episodes`.`series_id` = `series`.`id` WHERE `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY total_views DESC LIMIT 16;";
                generateSeriesSection($query, 'Most watched');

                $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` on `episodes`.`series_id` = `series`.`id` WHERE `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY RAND() LIMIT 16;";
                generateSeriesSection($query, 'Random choice');

                $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` on `episodes`.`series_id` = `series`.`id` WHERE `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY `series`.`added_date` DESC LIMIT 16;";
                generateSeriesSection($query, 'Just Added');

                $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` on `episodes`.`series_id` = `series`.`id` WHERE `genre` LIKE '%adventure%' AND `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY RAND() LIMIT 16;";
                generateSeriesSection($query, 'Adventure', '/search?q=&type=default&genre=Adventure&sort=default&cat=Adventure');

                $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` on `episodes`.`series_id` = `series`.`id` WHERE `genre` LIKE '%action%' AND `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY RAND() LIMIT 16;";
                generateSeriesSection($query, 'Action', '/search?q=&type=default&genre=Action&sort=default&cat=Action');

                $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` on `episodes`.`series_id` = `series`.`id` WHERE `genre` LIKE '%magia%' AND `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY RAND() LIMIT 16;";
                generateSeriesSection($query, 'Thriller', '/search?q=&type=default&genre=Thriller&sort=default&cat=Thriller');
                ?>
            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
</body>

</html>
<?php $con->close() ?>