<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'auth/db_con.php';
require_once 'auth/seriesAuth.php';
define('ptw-module', true);
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
    <link rel="stylesheet" href="style/series.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!-- HANDLERS -->
    <script src="scripts/ptw-js-handler.js"></script>
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Voso</title>
</head>
<body>
    <div class="wrapper">
        <?php
        require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main">
                <div class="series-data">
                    <div class="slideshow-container">
                        <?php
                        $posters_query = "SELECT `episodes`.`poster` FROM `episodes` WHERE `episodes`.`series_id` = ? ORDER BY RAND() LIMIT 5";
                        if ($stmt = $con->prepare($posters_query)) {
                            $stmt->bind_param("i", $series_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        
                            echo "<div class='slide'><img src='$poster'></div>";
                            $imgCount = 1;
                        
                            while ($res = $result->fetch_assoc()) {
                                echo "<div class='slide'><img src='{$res['poster']}'></div>";
                                $imgCount++;
                            }
                        
                            $stmt->close();
                        }
                        ?>

                        <a class="prev" onclick="plusSlides(-1)">❮</a>
                        <a class="next" onclick="plusSlides(1)">❯</a>

                        <div class="dot-wrapper">
                            <?php
                            for ($i = 0; $i < $imgCount; $i++) {
                                echo "<span class='dot'></span>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="titles">
                        <?php 
                            echo "<div class='title'><h2>$alt_title</h2>$title</div>";
                            if(isset($_SESSION['loggedin'])){
                                require_once 'scripts/ptw-status.php';  
                            }
                        ?>
                    </div>
                    <div class="genres">
                        <?php 
                            $genres = rtrim($genres, '; ');
                            $genreArray = explode("; ", $genres);
                            $genreColors = [
                                'Action' => '#1a9c1f',
                                'Adventure' => '#006666',
                                'Animation' => '#CC9900',
                                'Biography' => '#966550',
                                'Comedy' => '#b0ad5b',
                                'Crime' => '#333333',
                                'Documentary' => '#666666',
                                'Drama' => '#2e2f42',
                                'Family' => '#8da6a5',
                                'Fantasy' => '#163352',
                                'History' => '#523d2c',
                                'Horror' => '#030d1c',
                                'Mystery' => '#663399',
                                'Romance' => '#CC66CC',
                                'Sci-Fi' => '#056fa1',
                                'Sport' => '#CC3300',
                                'Thriller' => '#990000',
                                'War' => '#072908',
                                'Western' => '#966550',
                                'Superhero' => '#CC66CC',
                                'Science' => '#056fa1',
                                'Education' => '#CC9900',
                                'Reality' => '#666666',
                                'Fiction' => '#666666',
                                'Musical' => '#CC9900',
                                'Concert' => '#CC9900',
                                'Talk Show' => '#666666',
                                'Stand-up Comedy' => '#b0ad5b',
                                'True Crime' => '#990000',
                                'Faith & Spirituality' => '#CC9900',
                                'Anime' => '#CC66CC',
                                'Children & Family' => '#8da6a5',
                                'Classic' => '#2e2f42',
                                'Independent' => '#666666',
                                'International' => '#666666',
                                'Teen' => '#8da6a5',
                                'Adult Animation' => '#660066',
                                'Science & Nature' => '#056fa1',
                                'Social & Cultural' => '#666666'
                            ];

                        $html = '';
                            foreach ($genreArray as $genre) {
                                $color = $genreColors[$genre] ?? 'black';
                                $html .= "<a class='genre-item' href='/search?q=&genre={$genre}&cat={$genre}' style='background-color: {$color};'>{$genre}</a>";
                            }
                            echo $html;
                        ?>
                    </div>
                    <div class="info">
                        <?php
                            $brdStartFixed = date("d.m.Y", strtotime($brdStart));
                            $brdEndFixed = date("d.m.Y", strtotime($brdEnd));
                            echo <<< SERIES_DATA
                            <b>Season:</b> $season<br>
                            <b>Episodes:</b> $epCount<br>
                            <b>Broadcast start:</b> $brdStartFixed<br>
                            <b>Broadcast end:</b> $brdEndFixed
                            SERIES_DATA;
                        ?>
                    </div>
                    <div class="description">
                        <?php echo "<h4>Description:</h4><p>$desc</p>";?>
                    </div>
                </div>
                <script>
                    const desiredHeight = $(".series-data").outerHeight();
                </script>
                <div class="series-eplist">
                    <div class="eplist">
                        <h1 class="ep-list-header">Episodes</h1>
                        <div class="episodes">
                            <?php
                            $episodes_query = "SELECT `id`, `title`, `ep_number` FROM `episodes` 
                                WHERE `series_id` = ? 
                                AND `isActive` = 1 
                                ORDER BY `ep_number` ASC";
                            if ($stmt = $con->prepare($episodes_query)) {
                                $stmt->bind_param("i", $series_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    $episode_count = 0;
                                    while ($res = $result->fetch_assoc()) {
                                        $episode_count++;
                                        if ($episode_count <= 25) {
                                            echo "<a href='watch?v={$res['id']}'><div class='episode'><div class='epNum'>{$res['ep_number']}</div><div class='epTtl'>{$res['title']}</div></div></a>";
                                        } else {
                                            echo "<a href='watch?v={$res['id']}' class='hidden-episode'><div class='episode'><div class='epNum'>{$res['ep_number']}</div><div class='epTtl'>{$res['title']}</div></div></a>";
                                        }
                                    }
                                } else {
                                    echo "<p class='no-episodes'>No episodes<p>";
                                }

                                $stmt->close();
                            } else {
                                echo "<p class='no-episodes'>Database error. Try again later.<p>";
                            }
                            ?>
                        </div>
                        <?php if ($episode_count > 25) { ?>
                            <div class="show-more">Show all episodes</div>
                        <?php } ?>
                    </div>
                </div>

                <script>
                    const showMoreButton = document.querySelector(".show-more");
                    const hiddenEpisodes = document.querySelectorAll(".hidden-episode");

                    if (showMoreButton && hiddenEpisodes) {
                        showMoreButton.addEventListener("click", () => {
                            hiddenEpisodes.forEach((episode) => {
                                episode.style.display = "block";
                            });
                            showMoreButton.style.display = "none";
                        });
                    }
                </script>

            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
    </div>
    <script>

    </script>
    <script src="scripts/slideshow.js"></script>
    <script>
        $(".series-data").css('max-height', desiredHeight);
    </script>
</body>
</html>
<?php $con->close() ?>