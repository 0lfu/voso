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
    <link rel="stylesheet" href="https://vjs.zencdn.net/7.21.1/video-js.css">
    <link rel="stylesheet" href="style/manage-content.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!--  LIST TOGGLE  -->
    <script src="scripts/manage-content-listToggle.js" type="text/javascript"></script>
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Voso</title>
</head>

<body>
    <div class="wrapper">
        <?php
        $mngcontentActive = "class='active'";
        require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-column">
                <h1>Manage Content</h1>
                <div class="browse-section">
                    <?php
                    $seriesData_query = "SELECT `id`, `alt_title`, `isActive` FROM `series` ORDER BY `alt_title`";
                    $result = $con->query($seriesData_query);

                    if ($result->num_rows > 0) {
                        $output = "<table>
                                    <tr class='series-header'>
                                        <th>ID</th>
                                        <th>Serie name</th>
                                        <th class='actions-header'>Actions</th>
                                    </tr>";
                        while ($seriesRow = $result->fetch_assoc()) {
                            $seriesId = $seriesRow["id"];
                            $output .= "<tr class='series-row' data-series-id='$seriesId'>
                                            <td class='series_id'>{$seriesRow["id"]}</td>
                                            <td class='series_title'>{$seriesRow["alt_title"]}</td>
                                            <td class='series_actions'>
                                                <div class='actions flex v-mid'>";
                            $output .= "<a href='series?s={$seriesId}'><span class='fas fa-external-link'></span></a>";
                            if ($seriesRow['isActive'] == 1) {
                                $output .= "<a href='scripts/manage-content-actions?s={$seriesId}&action=hide'><span class='fas fa-eye'></span></a>";
                            } else if ($seriesRow['isActive'] == 0) {
                                $output .= "<a href='scripts/manage-content-actions?s={$seriesId}&action=show'><span class='fas fa-eye-slash'></span></a>";
                            }
                            $output .= "<a href='scripts/manage-content-actions?s={$seriesId}&action=edit'><span class='fas fa-edit'></span></a>
                                        <a href='scripts/manage-content-actions?s={$seriesId}&action=delete'><span class='fas fa-trash'></span></a>
                                    </div>
                                </td>
                            </tr>";
                            $episodesData_query = "SELECT `id`, `title`, `ep_number`, `isActive` FROM `episodes` WHERE `series_id` = '$seriesId' ORDER BY `ep_number` ASC";
                            $episodesResult = $con->query($episodesData_query);
                            if ($episodesResult->num_rows > 0) {
                                $output .= "<tr class='episodes-container'>
                                                <td colspan='3'>
                                                    <table>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Number</th>
                                                            <th>Episode name</th>
                                                            <th class='actions-header'>Actions</th>
                                                        </tr>";
                                while ($episodeRow = $episodesResult->fetch_assoc()) {
                                    $episodeId = $episodeRow["id"];
                                    $output .= "<tr class='episodes-row' data-episode-id='$episodeId'>
                                                    <td class='episode_id'>{$episodeId}</td>
                                                    <td class='episode_number'>{$episodeRow["ep_number"]}</td>
                                                    <td class='episode_title'>{$episodeRow["title"]}</td>
                                                    <td class='episode_actions'>
                                                        <div class='actions flex v-mid'>";
                                    if ($episodeRow['isActive'] == 1) {
                                        $output .= "<a href='watch?v={$episodeId}'><span class='fas fa-external-link'></span></a>
                                                    <a href='scripts/manage-content-actions?e={$episodeId}&action=hide'><span class='fas fa-eye'></span></a>";
                                    } else if ($episodeRow['isActive'] == 0) {
                                        $output .= "<a href='scripts/manage-content-actions?e={$episodeId}&action=show'><span class='fas fa-eye-slash'></span></a>";
                                    }
                                    $output .= "<a href='scripts/manage-content-actions?e={$episodeId}&action=edit'><span class='fas fa-edit'></span></a>
                                                <a href='scripts/manage-content-actions?e={$episodeId}&action=delete'><span class='fas fa-trash'></span></a>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                                $output .= "</table></td></tr>";
                            } else {
                                $output .= "<tr class='episodes-container'><td colspan='3'>This serie does not contain any episodes.</td></tr>";
                            }
                        }
                        $output .= "</table>";
                    } else {
                        $output = "<div class='no-series'>No series found.</div>";
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