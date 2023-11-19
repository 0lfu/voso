<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
global $con;
require_once 'auth/db_con.php';
require_once 'auth/episodeAuth.php';
require_once 'vendor/autoload.php';
define('like-module', true);
define('comments-module', true);
define('ptw-module', true);
define('converter-module', true);
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
    <link rel="stylesheet" href="style/watch.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!-- PLAYER UTILITY SCRIPTS -->
    <script src="scripts/like-handler.js"></script>
    <script src="scripts/download.js"></script>
    <!-- COMMENTS -->
    <script src="scripts/report-comment.js"></script>
    <!-- VIDEOJS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/video.js@7.21.5/dist/video-js.css">
    <script src="https://cdn.jsdelivr.net/npm/video.js@7.21.5/dist/video.js"></script>
    <!-- QUALITY LEVELS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@xiaoyexiang/videojs-resolution-switcher-v7@1.1.9/lib/videojs-resolution-switcher-v7.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@xiaoyexiang/videojs-resolution-switcher-v7@1.1.9/lib/videojs-resolution-switcher-v7.min.js"></script>
    <!-- REMEMBER TIMESTAMP -->
    <script src="scripts/rememberTime.js" type="text/javascript"></script>
    <!-- CHROMECAST-->
    <link href="https://cdn.jsdelivr.net/npm/@silvermine/videojs-chromecast@1.4.1/dist/silvermine-videojs-chromecast.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@silvermine/videojs-chromecast@1.4.1/dist/silvermine-videojs-chromecast.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"></script>
    <!-- MOBILE UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/videojs-mobile-ui@0.8.0/dist/videojs-mobile-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/videojs-mobile-ui@0.8.0/dist/videojs-mobile-ui.min.js"></script>
    <!-- HOTYKEYS -->
    <script src="https://cdn.jsdelivr.net/npm/videojs-hotkeys@0.2.28/videojs.hotkeys.min.js"></script>
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
                <div class="video-container">
                    <?php
                    $result = $con->query($episode_query);

                    $res = $result->fetch_assoc();

                    $id = $res['id'];
                    $series_id = $res['series_id'];
                    $url = $res['url'] ?? "";
                    $url2 = $res['url2'] ?? "";
                    $url3 = $res['url3'] ?? "";
                    $poster = $res['poster'] ?? "";
                    $title = $res['title'];
                    $ep_number = $res['ep_number'];
                    $isActive = $res['isActive'];
                    $desc = $res['desc'];
                    $added_date = $res['added_date'];
                    $views = $res['views'];
                    $likes = $res['likes'];
                    $intro_end = $res['intro_end'];
                    $intro_start = $res['intro_start'];

                    $result->free();

                    $views++;
                    $update_query = "UPDATE episodes SET views = $views WHERE id = $id";
                    $con->query($update_query);

                    if(isset($_SESSION['loggedin'])){
                        $checkifexist_query = "SELECT * FROM `history` INNER JOIN `accounts` ON `accounts`.`id` = `history`.`id` WHERE `accounts`.`id` = $_SESSION[id]";
                        $result = $con->query($checkifexist_query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $db = $row['last_watched'];
                                $db = rtrim($db, ';');
                                $episodes_list = explode(";", $row['last_watched']);
                                if (($key = array_search($id, $episodes_list)) !== false) {
                                    unset($episodes_list[$key]);
                                }
                                $updated_list = [$id, ...$episodes_list];
                                $updated_list = array_slice($updated_list, 0, 5);
                                $update_query_content = implode(";", $updated_list);
                                $updateHistory_query = "UPDATE `history` SET `last_watched`='$update_query_content' WHERE `id` = $row[id]";
                                $con->query($updateHistory_query);
                            }
                        } else {
                            $addToHistory_query = "INSERT INTO `history`(`id`, `last_watched`) VALUES ($_SESSION[id],'$id')";
                            $con->query($addToHistory_query);
                        }
                        $result->free();
                    }

                    $seriesInfo_query = "SELECT * FROM `series` WHERE `id` = $series_id";
                    $result = $con->query($seriesInfo_query);
                    if ($result->num_rows > 0) {
                        $res = $result->fetch_assoc();
                        $full_title = $res['title'];
                        $alt_title = $res['alt_title'];
                        $season = $res['season'];
                        $series_desc = $res['desc'];
                        $genre = $res['genre'];
                    }
                    $epDesc = $desc;
                    if (empty($epDesc)) {
                        $epDesc = $series_desc;
                    }


                    require_once 'scripts/url-converter.php';          
                    $regex_onedrive = '/.*onedrive\.live\.com.*/';
                    $regex_cda = '/.*cda\.pl.*/';
                    
                    $options = '';
                    $videoBuilder = '';
                    
                    $urls = array($url, $url2, $url3);
                    $cdaVideoUrls = array();

                    foreach ($urls as $link) {
                        if (!empty($link) && ($serviceType = getServiceType($link, $regex_onedrive, $regex_cda)) !== false) {
                            if ($serviceType === 'CDA') {
                                $cdaVideoUrls[$link] = downloadCdaVideo($link);
                                if (!empty($cdaVideoUrls[$link])) {
                                    $options .= '<li data-og="'.$link.'" data-url1080p="' . ($cdaVideoUrls[$link]['1080p'] ?? '') . '" data-url720p="' . ($cdaVideoUrls[$link]['720p'] ?? '') . '" data-url480p="' . ($cdaVideoUrls[$link]['480p'] ?? '') . '" data-url360p="' . ($cdaVideoUrls[$link]['360p'] ?? '') . '">' . $serviceType . '</li>';
                                }                                
                            } elseif ($serviceType === 'ONEDRIVE') {
                                $options .= '<li data-url="' . $link . '">' . $serviceType . '</li>';
                            }
                        }
                    }
                    $regex_onedrive = '/.*onedrive\.live\.com.*/';
                    $regex_cda = '/.*cda\.pl.*/';
                    if (!empty($url) && ($serviceType = getServiceType($url, $regex_onedrive, $regex_cda)) !== false) {
                        if ($serviceType === 'CDA') {
                            $videoBuilder = '<video id="o-video" class="video-js vjs-big-play-centered vjs-16-9" controls preload="auto" poster="'.$poster.'">';
                            if(isset($cdaVideoUrls[$url]['1080p']) && !empty($cdaVideoUrls[$url]['1080p'])) $videoBuilder .= '<source src="'.$cdaVideoUrls[$url]['1080p'].'" type="video/mp4" label="1080p">';
                            if(isset($cdaVideoUrls[$url]['720p']) && !empty($cdaVideoUrls[$url]['720p'])) $videoBuilder .= '<source src="'.$cdaVideoUrls[$url]['720p'].'" type="video/mp4" label="720p">';
                            if(isset($cdaVideoUrls[$url]['480p']) && !empty($cdaVideoUrls[$url]['480p'])) $videoBuilder .= '<source src="'.$cdaVideoUrls[$url]['480p'].'" type="video/mp4" label="480p">';
                            if(isset($cdaVideoUrls[$url]['360p']) && !empty($cdaVideoUrls[$url]['360p'])) $videoBuilder .= '<source src="'.$cdaVideoUrls[$url]['360p'].'" type="video/mp4" label="360p">';
                            $videoBuilder .= '<p class="vjs-no-js">To view this video enable JS or use a newer browser</p></video>';
                        } elseif ($serviceType === 'ONEDRIVE') {
                            $videoUrl = downloadOnedriveVideo($url);
                            $videoBuilder = '<video id="o-video" class="video-js vjs-big-play-centered vjs-16-9" controls preload="auto" poster="'.$poster.'">';
                            if(isset($videoUrl) && !empty($videoUrl)) $videoBuilder .= '<source src="'.$videoUrl.'" type="video/mp4">';
                            $videoBuilder .= '<p class="vjs-no-js">To view this video enable JS or use a newer browser</p></video>';
                        }
                    }
                    echo $videoBuilder;

                    echo "<div class='video-interactive'>";
                    require_once 'scripts/like-status.php';
                    require_once 'scripts/like-counter.php';
                    echo <<< VIDEO_INTERACTIVE
                        <a class="series-button" href="series?s=$series_id"><span class="fas fa-list-ol interactive-item"></span></a>
                        <span class="fas fa-download interactive-item download"></span>
                        <span class="fas fa-share-alt interactive-item share" onclick="navigator.clipboard.writeText(window.location.href);"></span>
VIDEO_INTERACTIVE;
                    $list = '<ul class="dropdown-list">' . $options . '</ul>';
                    echo <<< SOURCE_SELECT
                    <div class="source-wrapper">
                        <span class="fas fa-external-link interactive-item source-url"></span>
                        <span class="fas fa-flag-alt interactive-item report-episode"></span>
                        <div class="custom-dropdown">
                            <div class="dropdown-header">
                                <span class="sourceText">No sources available</span>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                            </div>
                            $list
                        </div>
                    </div>
                    SOURCE_SELECT;
                    echo <<< VIDEO_INTERACTIVE
                        </select>
                    </div>
                    <div class="video-meta">
                        <h2>$alt_title S{$season} O{$ep_number} - "$title"</h2>
                        <p class="description">$epDesc</p>
                    </div>
VIDEO_INTERACTIVE;
                    ?>
                    <div class="comments" id="comments"></div>

                    <script>
                        const comments_page_id = '<?php echo $id; ?>';
                        fetch("scripts/comments?video_id=" + comments_page_id).then(response => response.text()).then(data => {
                            document.querySelector(".comments").innerHTML = data;
                            document.querySelectorAll(".comments .reply_comment_btn").forEach(element => {
                                element.onclick = event => {
                                    event.preventDefault();
                                    document.querySelectorAll(".comments .write_comment").forEach(element => element.style.display = 'none');
                                    document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "']").style.display = 'block';
                                };
                            });
                            //report comment system
                            document.querySelectorAll(".comments .report_comment_btn").forEach(element => {
                                element.onclick = event => {
                                    event.preventDefault();
                                    $('#form-comment-id').val(element.getAttribute("data-comment-id"));
                                    $('.form-popup-bg').addClass('is-visible');
                                    $('.form-popup-bg').on('click', function(event) {
                                        if ($(event.target).is('#btnCloseForm')) {
                                            event.preventDefault();
                                            $(this).removeClass('is-visible');
                                            $('select.form-control option:first-child').prop('selected', true);
                                            $('#report-form textarea').val('');
                                        }
                                    });

                                    function closeForm() {
                                        $('.form-popup-bg').removeClass('is-visible');
                                    }
                                };
                            });
                            //open source url
                            $(".source-url").click(function(){
                                const redirect = $('.dropdown-list li').data('og');
                                window.open(redirect, '_blank');
                                return false;
                            });
                            //report episode system
                            $(".report-episode").click(function(){
                                $('.form-report-player').addClass('is-visible');
                                $('.form-report-player').on('click', function(event) {
                                        if ($(event.target).is('#btnCloseForm')) {
                                            event.preventDefault();
                                            $(this).removeClass('is-visible');
                                        }
                                    });
                                function closeForm() {
                                    $('.form-report-player').removeClass('is-visible');
                                }
                            });
                            //comment system
                            document.querySelectorAll(".comments .write_comment form").forEach(element => {
                                element.onsubmit = event => {
                                    event.preventDefault();
                                    fetch("scripts/comments?video_id=" + comments_page_id, {
                                        method: 'POST',
                                        body: new FormData(element)
                                    }).then(response => response.text()).then(data => {
                                        element.parentElement.innerHTML = data;
                                        document.location.reload(true);
                                    });
                                };
                            });
                        });
                    </script>
                </div>
                <div class="suggested">
                    <?php
                    $nextEp_query = "SELECT `episodes`.`id`, `episodes`.`poster`, `series`.`season`, `series`.`brd-type`, `episodes`.`ep_number`
                    FROM `episodes`
                    INNER JOIN `series` ON `series`.`id` = `episodes`.`series_id`
                    WHERE `series`.`id` = (SELECT `episodes`.`series_id` from `episodes` WHERE `episodes`.`id` = $id)
                    AND `episodes`.`isActive` = 1
                    AND (
                        (`episodes`.`ep_number` = (SELECT `episodes`.`ep_number` from `episodes` WHERE `episodes`.`id` = $id) + 1)
                        OR (
                            `episodes`.`ep_number` > (SELECT `episodes`.`ep_number` from `episodes` WHERE `episodes`.`id` = $id)
                            AND NOT EXISTS (
                                SELECT *
                                FROM `episodes`
                                WHERE `series_id` = (SELECT `episodes`.`series_id` from `episodes` WHERE `episodes`.`id` = $id)
                                AND `ep_number` = (SELECT `episodes`.`ep_number` from `episodes` WHERE `episodes`.`id` = $id) + 1
                                AND `isActive` = 1
                            )
                        )
                    )
                    ORDER BY `episodes`.`ep_number`
                    LIMIT 1;
                    ";
                    $result = $con->query($nextEp_query);
                    if ($result->num_rows > 0) {
                        $res = $result->fetch_assoc();
                        $nextEp_id = $res['id'];
                        $nextEp_poster = $res['poster'];
                        $nextEp_season = $res['season'];
                        $nextEp_num = $res['ep_number'];

                        $brdType = strtolower($res['brd-type']);
                        if ($brdType === 'series') {
                            $badgeText = "S{$nextEp_season} O{$nextEp_num}";
                        } elseif ($brdType === 'movie') {
                            $badgeText = "Movie";
                        }
                        echo <<< NEXT_EP
                            <h2>Next episode:</h2>
                            <a class="next-ep-box" href="watch?v=$nextEp_id">
                                <img width="100%" src="$nextEp_poster">
                                <span class='next-ep-numinfo'>$badgeText</span>
                                <p>$alt_title</p>
                            </a>
NEXT_EP;
                    }
                    $result->free();

                    $stmt = $con->prepare("SELECT genre FROM series WHERE id = ?");
                    $stmt->bind_param("i", $series_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $genre_string = $result->fetch_assoc()['genre'];
                    $genre_string = rtrim($genre_string, '; ');
                    $genres = explode('; ', $genre_string);
                    $genres = array_map('trim', $genres);
                    $genres = array_filter($genres);

                    $stmt = $con->prepare("SELECT id, genre FROM series WHERE id != ?");
                    $stmt->bind_param("i", $series_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $series_ids = [];
                    while ($row = $result->fetch_assoc()) {
                        $series_ids[] = $row['id'];
                    }
                    shuffle($series_ids);
                    echo "<h2 style='margin-top:4%;'>Similar series:</h2>";
                    $ile = 0;

                    foreach ($series_ids as $sid) {
                        $stmt = $con->prepare("SELECT * FROM series WHERE id = ?");
                        $stmt->bind_param("i", $sid);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();

                        $brdType = strtolower($row['brd-type']);
                        if ($brdType === 'series') {
                            $badgeText = "Season {$row['season']}";
                        } elseif ($brdType === 'movie') {
                            $badgeText = "Movie";
                        }

                        $genre_string = $row['genre'];
                        $genre_string = rtrim($genre_string, '; ');
                        $series_genres = explode('; ', $genre_string);
                        $series_genres = array_map('trim', $series_genres);
                        $series_genres = array_filter($series_genres);

                        if (count(array_intersect($genres, $series_genres)) > 0) {
                            echo <<< SUGESTED
                            <a class="sugested-series" href="series?s=$sid">
                                <img width="100%" src="{$row['poster']}">
                                <span class='sugested-season'>$badgeText</span>
                                <div class="sugested-title">{$row['alt_title']}</div>
                            </a>
                            SUGESTED;
                            $ile++;
                        }
                        if ($ile == 3) break;
                    }
                    ?>
                </div>
            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
    </div>

    <div class="form-popup-bg">
        <div class="form-container">
            <span id="btnCloseForm" class="fas fa-times"></span>
            <h1>Report comment</h1>
            <form id="report-form">
                <input type="text" id="form-comment-id" name="comment-id" hidden>
                <div class="form-group">
                    <label>Reason</label>
                    <select class="form-control" name="reason">
                        <option>It's offensive</option>
                        <option>Advertisement</option>
                        <option>Spam</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Additional notes: (optional)</label>
                    <textarea maxlength="1024" rows="3" class="form-control" name="note"></textarea>
                </div>
                <button type="submit">Submit report</button>
            </form>
        </div>
    </div>

    <div class="form-report-player">
        <div class="form-container">
            <span id="btnCloseForm" class="fas fa-times"></span>
            <h1>Report player</h1>
            <form id="report-player-form" action="scripts/report-player-handle" method="POST">
                <div class="form-group">
                    <label>Are you sure you want to report this episode because the player is not working?</label>
                    <input type="text" name="player-id" value="<?php echo $id;?>" hidden>
                </div>
                <button type="submit">Submit report</button>
            </form>
        </div>
    </div>
    <script>
        const endIntroTime = "<?php echo $intro_end; ?>";
        const startIntroTime = "<?php echo $intro_start; ?>";
        const episodeTitle = "<?php echo $alt_title; ?>";
    </script>
    <script src="scripts/videojs-options.js"></script>
    <script>
        const player = videojs('o-video', options);
        player.chromecast({addButtonToControlBar: false});
        player.addChild('chromecastButton'); 
        player.mobileUi();
        videojs(document.querySelector('video')).remember({"localStorageKey": "videojs.remember.myvideo"});
    </script>
    <script src="scripts/videojs-behavior.js"></script>
    <script src="scripts/introSkip.js"></script>
    <script src="scripts/resolutionSwitch.js"></script>
    <script src="scripts/dropdownHandler.js"></script>
    <script src="scripts/downloadVideo.js"></script>
</body>

</html>
<?php $con->close() ?>