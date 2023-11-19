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
    <link rel="stylesheet" href="style/edit-content.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!-- ADJUST ITEM SIZE -->
    <script src="scripts/multiselect-dropdown.js"></script>
    <script src="scripts/adjust-size.js"></script>
    <!-- VALIDATE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Voso</title>
</head>

<body>
    <div class="wrapper">
        <?php require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-row">
                <?php
                if (!empty($_GET['s'])) {
                    $seriesData_query = "SELECT * FROM `series` WHERE `id` = ?";
                    $stmt = $con->prepare($seriesData_query);
                    $stmt->bind_param("i", $_GET['s']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        header('Location: manage-content?e=seriesnotexist');
                        exit;
                    } else {
                        $res = $result->fetch_assoc();
                        $series_id = htmlspecialchars($res['id'], ENT_QUOTES);
                        $title = htmlspecialchars($res['title'], ENT_QUOTES);
                        $alt_title = htmlspecialchars($res['alt_title'], ENT_QUOTES);
                        $season = htmlspecialchars($res['season'], ENT_QUOTES);
                        $poster = htmlspecialchars($res['poster'], ENT_QUOTES);
                        $desc = htmlspecialchars($res['desc'], ENT_QUOTES);
                        $genre = htmlspecialchars($res['genre'], ENT_QUOTES);
                        $brdType = htmlspecialchars($res['brd-type'], ENT_QUOTES);
                        $brdStart = htmlspecialchars($res['brd-start'], ENT_QUOTES);
                        $brdEnd = htmlspecialchars($res['brd-end'], ENT_QUOTES);
                        $epCount = htmlspecialchars($res['ep_count'], ENT_QUOTES);
                        $tags = htmlspecialchars($res['tags'], ENT_QUOTES);

                        $optionSeries = "<option value='Series'>Series</option>";
                        $optionFilm = "<option value='Movie'>Movie</option>";

                        if ($brdType == 'Series') {
                            $optionSeries = "<option value='Series' selected>Series</option>";
                        } elseif ($brdType == 'Movie') {
                            $optionFilm = "<option value='Movie' selected>Movie</option>";
                        }
                        $genre =  rtrim($genre, '; ');
                        $genre = explode("; ", $genre);
                        $options = array("Action", "Adventure", "Animation", "Biography", "Comedy", "Crime", "Documentary", "Drama", "Family", "Fantasy", "History", "Horror", "Mystery", "Romance", "Sci-Fi", "Sport", "Thriller", "War", "Western", "Superhero", "Science", "Education", "Reality", "Fiction", "Musical", "Concert", "Talk Show", "Stand-up Comedy", "True Crime", "Faith & Spirituality", "Anime", "Children & Family", "Classic", "Independent", "International", "Teen", "Adult Animation", "Science & Nature", "Social & Cultural");
                        $genreOptions = "";
                        foreach ($options as $option) {
                            $genreOptions .= ('<option ' . (in_array($option, $genre) ? 'selected' : '') . '>' . $option . '</option>');
                        }
                        echo <<< SERIES_EDIT_FORM
                        <div class="edit-series-form">
                        <h1>Series edit</h1>
                        <form id="form-editSeries" method="POST" action="scripts/update_series">
                            <div class="form-group">
                                <div class="form-item id">
                                    <label>ID</label>
                                    <div class="flex flex-row v-mid">
                                        <input id="form-seriesId" type="text" name="id" min="100000000" max="999999999" value='$series_id' readonly required>
                                    </div>
                                </div>
                                <div class="form-item altname">
                                    <label>Common name</label>
                                    <input type="text" name="altname" value='$alt_title' required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item fullname">
                                    <label>Full name</label>
                                    <input type="text" name="fullname" value='$title' required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item season">
                                    <label>Season</label>
                                    <input type="number" min="0" name="season" value='$season' required>
                                </div>
                                <div class="form-item epcount">
                                    <label>Number of episodes</label>
                                    <input type="number" min="0" name="epcount" value='$epCount' required>
                                </div>
                                <div class="form-item brdtype">
                                    <label>Type</label>
                                    <select name="brdtype">
                                        $optionSeries
                                        $optionFilm
                                    </select>
                                </div>
                                <div class="form-item brdstart">
                                    <label>Broadcast start</label>
                                    <input type="date" name="brdstart" value='$brdStart' required>
                                </div>
                                <div class="form-item brdend">
                                    <label>Broadcast end</label>
                                    <input type="date" name="brdend" value='$brdEnd' required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item genre">
                                    <label>Genre</label>
                                    <select multiple multiselect-search="true" name="genre[]">
                                    $genreOptions
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item desc">
                                    <label>Description</label>
                                    <textarea name="desc" rows="4" maxlength="1024" required>$desc</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item poster">
                                    <label>Thumbnail URL</label>
                                    <input type="text" name="poster" value='$poster' required>
                                </div>
                                <div class="form-item tags">
                                    <label>Tags</label>
                                    <input type="text" name="tags" value='$tags'>
                                </div>
                            </div>
                            <div class="form-group form-controls">
                                <div class="form-item submit">
                                    <input type="submit" value="Save">
                                </div>
                                <div class="form-item cancel">
                                    <a href="/manage-content">Dismiss</a>
                                </div>
                            </div>
                        </form>
                        <script src="scripts/validate-series.js"></script>
                    </div>
SERIES_EDIT_FORM;
                    }
                } else if (!empty($_GET['e'])) {
                    $seriesData_query = "SELECT * FROM `episodes` WHERE `id` = ?";
                    $stmt = $con->prepare($seriesData_query);
                    $stmt->bind_param("i", $_GET['e']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        header('Location: manage-content?e=episodenotexist');
                        exit;
                    } else {
                        $res = $result->fetch_assoc();
                        $id = htmlspecialchars($res['id'], ENT_QUOTES);
                        $series_id = htmlspecialchars($res['series_id'], ENT_QUOTES);
                        $url = htmlspecialchars($res['url'] ?? "", ENT_QUOTES);
                        $url2 = htmlspecialchars($res['url2'] ?? "", ENT_QUOTES);
                        $url3 = htmlspecialchars($res['url3'] ?? "", ENT_QUOTES);
                        $poster = htmlspecialchars($res['poster'], ENT_QUOTES);
                        $title = htmlspecialchars($res['title'], ENT_QUOTES);
                        $ep_number = htmlspecialchars($res['ep_number'], ENT_QUOTES);
                        $isActive = htmlspecialchars($res['isActive'], ENT_QUOTES);
                        $desc = htmlspecialchars($res['desc'], ENT_QUOTES);
                        $added_date = htmlspecialchars($res['added_date'], ENT_QUOTES);
                        $likes = htmlspecialchars($res['likes'], ENT_QUOTES);
                        $intro_start = htmlspecialchars($res['intro_start'], ENT_QUOTES);
                        $intro_end = htmlspecialchars($res['intro_end'], ENT_QUOTES);                        
                        
                        $startMinutes = floor((int)$intro_start / 60);
                        $startSeconds = (int)$intro_start % 60;
                        $endMinutes = floor((int)$intro_end / 60);
                        $endSeconds = (int)$intro_end % 60;
                        
                        $isChecked = $isActive = 1 ? "checked" : "";

                        $query = "SELECT `id`, `alt_title`, `season` FROM `series` ORDER BY `alt_title` ASC";
                        $stmt = $con->prepare($query);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $options = "";
                        while ($row = $result->fetch_assoc()) {
                            if ($series_id == $row['id']) {
                                $options .= "<option value='$row[id]' selected>$row[alt_title] [S{$row['season']}]</option>";
                            } else {
                                $options .= "<option value='$row[id]'>$row[alt_title] [S{$row['season']}]</option>";
                            }
                        }

                        echo <<< EPISODE_EDIT_FORM
                        <div class="edit-episode-form">
                        <h1>Episode edit</h1>
                        <form id="form-editEpisode" method="POST" action="scripts/update_episode">
                            <div class="form-group">
                                <div class="form-item id">
                                    <label>ID</label>
                                    <div class="flex flex-row v-mid">
                                        <input id="form-episodeId" type="text" name="id" min="100000000" max="999999999" readonly value='$id' required>
                                    </div>
                                </div>
                                <div class="form-item series">
                                    <label>Seria</label>
                                    <select name="series" required>
                                        $options
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item ep-title">
                                    <label>Episode title</label>
                                    <input type="text" name="title" value='$title' required>
                                </div>
                                <div class="form-item ep-number">
                                    <label>Episode number</label>
                                    <input type="number" name="number" min="0" value='$ep_number' required>
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="form-item ep-url">
                                    <label>Video URL 1</label>
                                    <input type="url" name="url" value='$url' required>
                                </div>
                                <div class="form-item ep-poster">
                                    <label>Thumbnail URL</label>
                                    <input type="url" name="poster" value='$poster' required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item ep-url2">
                                    <label>Video URL 2</label>
                                    <input type="url" value='$url2' name="url2">
                                </div>
                                <div class="form-item ep-url3">
                                    <label>Video URL 3</label>
                                    <input type="url" value='$url3' name="url3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item desc">
                                    <label>Description</label>
                                    <textarea name="desc" rows="4" maxlength="2048">$desc</textarea>
                                </div>
                            </div>
                            <div class="form-group" style="justify-content: left;">
                                <div class="form-item intro-start">
                                    <label>Intro start</label>
                                    <div class="flex flex-row v-mid">
                                        <input id="intro-start-min" type="number" name="start-minutes" min="0" value='$startMinutes'>
                                        &nbsp;<h1>:</h1>&nbsp;
                                        <input type="number" name="start-seconds" min="0" max="59" value='$startSeconds'>
                                    </div>
                                </div>
                                <div class="form-item intro-end">
                                    <label>Intro end</label>
                                    <div class="flex flex-row v-mid">
                                        <input id="intro-min" type="number" name="end-minutes" min="0" value='$endMinutes'>
                                        &nbsp;<h1>:</h1>&nbsp;
                                        <input type="number" name="end-seconds" min="0" max="59" value='$endSeconds'>
                                    </div>
                                </div>
                                <div class="form-item isVisible">
                                    <label>Is public</label>
                                    <input type="checkbox" name="visible" $isChecked>
                                </div>
                            </div>
                            <div class="form-group form-controls">
                                <div class="form-item submit">
                                    <input type="submit" value="Save">
                                </div>
                                <div class="form-item cancel">
                                    <a href="/manage-content">Dismiss</a>
                                </div>
                            </div>
                        </form>
                        <script src="scripts/validate-episode.js"></script>
                    </div>
EPISODE_EDIT_FORM;
                    }
                } else {
                    header('Location: manage-content?e=error');
                    exit;
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php $con->close() ?>