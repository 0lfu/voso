<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'auth/db_con.php';
require_once 'vendor/autoload.php';
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
    <link rel="stylesheet" href="style/list.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!--  ANCHOR  -->
    <script src="scripts/list-anchor.js" type="text/javascript"></script>
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="logo/favicon.png"/>
    <title>Voso</title>
</head>

<body>
    <div class="wrapper">
        <?php
        $listActive = "class='active'";
        require_once 'components/left-menu.php';
        ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-column">
                <h1>Series List</h1>
                <div class="byletter">
                    <?php
                    echo "<a class='letter' href='#0-9'>0-9</a>";
                    $alphabet = range('A', 'Z');
                    foreach ($alphabet as $letter) {
                        echo "<a class='letter' href='#$letter'>$letter</a>";
                    }
                    ?>
                </div>
                <div class="list-section">
                    <?php
                    $query = "SELECT * FROM series WHERE isActive = 1 ORDER BY title;";
                    if ($result = $con->query($query)) {
                        $currentLetter = '';
                        while ($row = $result->fetch_assoc()) {
                            $brdType = strtolower($row['brd-type']);
                            if ($brdType === 'series') {
                                $badgeText = "Season {$row['season']}";
                            } elseif ($brdType === 'movie') {
                                $badgeText = "Movie";
                            }
                            $title = $row['title'];
                            $firstLetter = strtoupper($title[0]);

                            // Handle numbers (0-9)
                            if (is_numeric($firstLetter)) {
                                $firstLetter = '0-9';
                            }

                            if ($firstLetter !== $currentLetter) {
                                if ($currentLetter !== '') {
                                    echo "</div>";
                                }
                                echo "<div class='list-item-separator' id='$firstLetter'>";
                                echo "<h2>$firstLetter</h2>";
                                echo "<hr>";
                                echo "</div>";
                                echo "<div class='list-item-series'>";
                                $currentLetter = $firstLetter;
                            }

                            echo "<a href='series?s=$row[id]'>";
                            echo "<div class='list-item'>";
                            echo "<img src='$row[poster]'>";
                            echo "<div class='list-data'>";
                            echo "<p>$title</p>";
                            echo "<span class='badge'>$badgeText</span>";
                            echo "</div>";
                            echo "</div>";
                            echo "</a>";
                        }

                        echo "</div>";
                        $result->free();
                    }
                    ?>
                </div>
            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
    </div>
</body>

</html>
<?php $con->close() ?>
