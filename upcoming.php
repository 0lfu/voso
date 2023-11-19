<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
        <link rel="stylesheet" href="style/upcoming.css">
        <!-- JQUERY -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <!-- NOTY.JS -->
        <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
        <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
        <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
        <script type="text/javascript" src="scripts/notifications.js"></script>
        <!-- CALENDAR -->
        <script src="scripts/calendar.js"></script>
        <!-- TITLE AND FAVICON -->
        <link rel="icon" type="image/png" href="logo/favicon.png"/>
        <title>Voso</title>
    </head>

    <body>
    <div class="wrapper">
        <?php
        $upcomingActive = "class='active'";
        require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-column">
                <h1>Upcoming</h1>
                <div class="comingsoon-section">
                    <div id="calendar-container">
                        <div id="calendar-controls">
                            <div id="month-back"><span class="fas fa-caret-left"></span></div>
                            <div id="month"></div>
                            <div id="month-forward"><span class="fas fa-caret-right"></span></div>
                        </div>
                        <div id="calendar-header">
                            <div class="day-of-week"><span>Monday</span></div>
                            <div class="day-of-week"><span>Tuesday</span></div>
                            <div class="day-of-week"><span>Wednesday</span></div>
                            <div class="day-of-week"><span>Thursday</span></div>
                            <div class="day-of-week"><span>Friday</span></div>
                            <div class="day-of-week"><span>Saturday</span></div>
                            <div class="day-of-week"><span>Sunday</span></div>
                        </div>
                        <div id="calendar"></div>
                    </div>
                    <div class="summary-container">
                        <h3>Releases</h3>
                        <?php
                        $query1 = "SELECT * FROM `series` WHERE `brd-start` > CURRENT_DATE() ORDER BY `brd-start` ASC";
                        if ($stmt1 = $con->prepare($query1)) {
                            $stmt1->execute();
                            $result1 = $stmt1->get_result();

                            $previous_month = null;
                            $months = array(
                                1 => 'January',
                                2 => 'February',
                                3 => 'March',
                                4 => 'April',
                                5 => 'May',
                                6 => 'June',
                                7 => 'July',
                                8 => 'August',
                                9 => 'September',
                                10 => 'October',
                                11 => 'November',
                                12 => 'December'
                            );

                            if ($result1->num_rows > 0) {
                                echo "<div class='summary-item'>";

                                while ($row1 = $result1->fetch_assoc()) {
                                    $current_month_num = date('n', strtotime($row1['brd-start']));
                                    $current_month = $months[$current_month_num];

                                    if ($current_month !== $previous_month) {
                                        if ($previous_month !== null) {
                                            echo "</div>";
                                        }
                                        echo "<h4 class='month-name'>$current_month:</h4><div class='upcoming-container'>";
                                        $previous_month = $current_month;
                                    }

                                    echo "<a href='series?s={$row1['id']}'>
                                            <div class='upcoming-item'>
                                                <div class='upcoming-data'>
                                                    {$row1['title']}
                                                </div>
                                            </div>
                                        </a>";
                                }

                                echo "</div>";
                            } else {
                                echo "<div class='no-releases'>No releases.</div>";
                            }

                            $stmt1->close();
                        } else {
                            header("location: /browse?e=error");
                            exit;
                        }

                        ?>
                    </div>
                </div>
            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
    </div>
    </body>

    </html>
<?php $con->close() ?>