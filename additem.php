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
    <link rel="stylesheet" href="style/additem.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!-- ADJUST ITEM SIZE AND REG ID -->
    <script src="scripts/add_item-tabSwitch.js"></script>
    <script src="scripts/multiselect-dropdown.js"></script>
    <script src="scripts/adjust-size.js"></script>
    <script src="scripts/add_item-regenerateId.js"></script>
    <!-- VALIDATE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Voso</title>
</head>

<body>
    <div class="wrapper">
        <?php
        $additemActive = "class='active'";
        require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-row">
                <div class="add-series-button active">
                    <h3>Add series</h3>
                </div>
                <div class="add-episode-button">
                    <h3>Add episode</h3>
                </div>
                <div class="add-many-button">
                    <h3>Add many episodes</h3>
                </div>
                <div class="add-series-form active">
                    <form id="form-addSeries" method="POST" action="scripts/add_series">
                        <div class="form-group">
                            <div class="form-item id">
                                <label>ID</label>
                                <div class="flex flex-row v-mid">
                                    <input id="form-seriesId" type="text" name="id" min="100000000" max="999999999" readonly="readonly" required>
                                    <span class="fas fa-redo regenerate-seriesID-button"></span>
                                </div>
                            </div>
                            <div class="form-item altname">
                                <label>Common name</label>
                                <input type="text" name="altname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item fullname">
                                <label>Full name</label>
                                <input type="text" name="fullname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item season">
                                <label>Season</label>
                                <input type="number" min="0" value="1" name="season" required>
                            </div>
                            <div class="form-item epcount">
                                <label>Number of episodes</label>
                                <input type="number" min="0" value="12" name="epcount" required>
                            </div>
                            <div class="form-item brdtype">
                                <label>Type</label>
                                <select name="brdtype" required>
                                    <option selected>Series</option>
                                    <option>Movie</option>
                                </select>
                            </div>
                            <div class="form-item brdstart">
                                <label>Broadcast Start</label>
                                <input type="date" name="brdstart" required>
                            </div>
                            <div class="form-item brdend">
                                <label>Broadcast End</label>
                                <input type="date" name="brdend" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item genre">
                                <label>Genre</label>
                                <select multiple multiselect-search="true" name="genre[]">
                                    <option>Action</option>
                                    <option>Adventure</option>
                                    <option>Animation</option>
                                    <option>Biography</option>
                                    <option>Comedy</option>
                                    <option>Crime</option>
                                    <option>Documentary</option>
                                    <option>Drama</option>
                                    <option>Family</option>
                                    <option>Fantasy</option>
                                    <option>History</option>
                                    <option>Horror</option>
                                    <option>Mystery</option>
                                    <option>Romance</option>
                                    <option>Sci-Fi</option>
                                    <option>Sport</option>
                                    <option>Thriller</option>
                                    <option>War</option>
                                    <option>Western</option>
                                    <option>Superhero</option>
                                    <option>Science</option>
                                    <option>Education</option>
                                    <option>Reality</option>
                                    <option>Fiction</option>
                                    <option>Musical</option>
                                    <option>Concert</option>
                                    <option>Talk Show</option>
                                    <option>Stand-up Comedy</option>
                                    <option>True Crime</option>
                                    <option>Faith & Spirituality</option>
                                    <option>Anime</option>
                                    <option>Children & Family</option>
                                    <option>Classic</option>
                                    <option>Independent</option>
                                    <option>International</option>
                                    <option>Teen</option>
                                    <option>Adult Animation</option>
                                    <option>Science & Nature</option>
                                    <option>Social & Cultural</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item desc">
                                <label>Description</label>
                                <textarea name="desc" rows="4" maxlength="1024" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item poster">
                                <label>Poster URL</label>
                                <input type="text" name="poster" required>
                            </div>
                            <div class="form-item tags">
                                <label>Tags</label>
                                <input type="text" name="tags">
                            </div>
                        </div>
                        <div class="form-group form-controls">
                            <div class="form-item submit">
                                <input type="submit" value="Add series">
                            </div>
                            <div class="form-item reset">
                                <input type="reset" value="Clear">
                            </div>
                        </div>
                    </form>
                    <script src="scripts/validate-series.js"></script>
                </div>
                <div class="add-episode-form">
                    <form id="form-addEpisode" method="POST" action="scripts/add_episode">
                        <div class="form-group">
                            <div class="form-item id">
                                <label>ID</label>
                                <div class="flex flex-row v-mid">
                                    <input id="form-episodeId" type="text" name="id" min="100000000" max="999999999" readonly="readonly" required>
                                    <span class="fas fa-redo regenerate-episodeID-button"></span>
                                </div>
                            </div>
                            <div class="form-item series">
                                <label>Series</label>
                                <select name="series" required id="series-dropdown">
                                    <option disabled selected value="">Select series</option>
                                    <?php
                                    $query = "SELECT `id`, `alt_title`, `season` FROM `series` ORDER BY `alt_title` ASC";
                                    $result = $con->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='$row[id]'>$row[alt_title] [S{$row['season']}]</option>";
                                    }
                                    $result->free();
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item ep-title">
                                <label>Episode title</label>
                                <input type="text" name="title" required>
                            </div>
                            <div class="form-item ep-number">
                                <label>Episode number</label>
                                <input type="number" name="number" min="0" required id="episode-number-input">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item ep-url">
                                <label>Video URL 1</label>
                                <input type="url" name="url" required>
                            </div>
                            <div class="form-item ep-poster">
                                <label>Thumbnail URL</label>
                                <input type="url" name="poster" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item ep-url2">
                                <label>Video URL 2</label>
                                <input type="url" name="url2">
                            </div>
                            <div class="form-item ep-url3">
                                <label>Video URL 3</label>
                                <input type="url" name="url3">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item desc">
                                <label>Description</label>
                                <textarea name="desc" rows="3" maxlength="2048"></textarea>
                            </div>
                        </div>
                        <div class="form-group" style="justify-content: left;">
                            <div class="form-item intro-start">
                                <label>Intro start</label>
                                <div class="flex flex-row v-mid">
                                    <input id="intro-start-min" type="number" name="start-minutes" min="0">
                                    &nbsp;<h1>:</h1>&nbsp;
                                    <input type="number" name="start-seconds" min="0" max="59">
                                </div>
                            </div>
                            <div class="form-item intro-end">
                                <label>Intro end</label>
                                <div class="flex flex-row v-mid">
                                    <input id="intro-min" type="number" name="end-minutes" min="0">
                                    &nbsp;<h1>:</h1>&nbsp;
                                    <input type="number" name="end-seconds" min="0" max="59">
                                </div>
                            </div>
                            <div class="form-item isVisible">
                                <label>Is public</label>
                                <input type="checkbox" name="visible" checked>
                            </div>
                        </div>
                        <div class="form-group form-controls">
                            <div class="form-item submit">
                                <input type="submit" value="Add episode">
                            </div>
                            <div class="form-item reset">
                                <input type="reset" value="Clear">
                            </div>
                        </div>
                    </form>
                    <script src="scripts/validate-episode.js"></script>
                    <script>
                        document.getElementById("series-dropdown").addEventListener("change", function () {
                            const selectedSeriesId = this.value;
                            if (selectedSeriesId) {
                                fetch(`scripts/get_last_episode_number.php?series_id=${selectedSeriesId}`)
                                    .then(response => response.text())
                                    .then(data => {
                                        const cleanedData = data.trim();

                                        const episodeNumberInput = document.getElementById("episode-number-input");
                                        episodeNumberInput.value = parseFloat(cleanedData)+1;
                                    })
                                    .catch(error => {
                                        console.error(error);
                                    });
                            }
                        });
                    </script>
                </div>
                <div class="add-many-form">
                    <form id="form-addMany" method="POST" action="scripts/add_many">
                        <div class="form-item series">
                            <label>Series</label>
                            <select name="series" required>
                                <option disabled selected value="">Select series</option>
                                <?php
                                $query = "SELECT `id`, `alt_title`, `season` FROM `series` ORDER BY `alt_title` ASC";
                                $result = $con->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='$row[id]'>$row[alt_title] [S{$row['season']}]</option>";
                                }
                                $result->free();
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-item videolinks">
                                <label>Video URLs</label>
                                <textarea name="videolinks" required></textarea>
                            </div>
                            <div class="form-item thumbnailinks">
                                <label>Thumbnail URLs</label>
                                <textarea name="thumbnailinks" required></textarea>
                            </div>
                            <div class="form-item titles">
                                <label>Titles</label>
                                <textarea name="titles" required></textarea>
                            </div>
                        </div>
                        <div class="form-group form-controls">
                            <div class="form-item submit">
                                <input type="submit" value="Add many episodes">
                            </div>
                            <div class="form-item reset">
                                <input type="reset" value="Clear">
                            </div>
                        </div>
                    </form>
                    <script src="scripts/validate-many.js"></script>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php $con->close() ?>