<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'auth/db_con.php';
require_once 'vendor/autoload.php';
if(!isset($_GET['q'])) {
    header('location: browse');
    exit;
}

function fetchSeriesData($con, $typeCondition, $genreCondition) {
    $sql = "SELECT *
            FROM series
            WHERE `isActive` = 1
            AND `brd-type` LIKE ?
            AND `genre` LIKE ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $typeCondition, $genreCondition);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$search = !empty($_GET['q']) ? $con->real_escape_string($_GET['q']) : null;
$type = $_GET['type'] ?? 'default';
$genre = $_GET['genre'] ?? 'default';
$sort = $_GET['sort'] ?? 'newest';

$typeCondition = ($type === 'default') ? "%" : "%{$type}%";
$genreCondition = ($genre === 'default') ? "%" : "%{$genre}%";

$orderColumn = '';
$orderDirection = '';

if ($sort === 'newest') {
    $orderColumn = 'brd-start';
    $orderDirection = 'DESC';
} elseif ($sort === 'oldest') {
    $orderColumn = 'brd-start';
    $orderDirection = 'ASC';
} elseif ($sort === 'name_asc') {
    $orderColumn = 'title';
    $orderDirection = 'ASC';
} elseif ($sort === 'name_desc') {
    $orderColumn = 'title';
    $orderDirection = 'DESC';
} elseif ($sort === 'default') {
    $orderColumn = 'id';
    $orderDirection = 'ASC';
}

$series = fetchSeriesData($con, $typeCondition, $genreCondition);

$options = [
    'keys' => ['alt_title', 'title', 'tags'],
    'threshold' => 0.3
];

$fuse = new Fuse\Fuse($series, $options);

$results = $search !== null ? $fuse->search($search) : $series;

usort($results, function ($a, $b) use ($orderColumn, $orderDirection) {
    if ($a[$orderColumn] === $b[$orderColumn]) {
        return 0;
    }
    return ($orderDirection === 'ASC')
        ? ($a[$orderColumn] < $b[$orderColumn] ? -1 : 1)
        : ($a[$orderColumn] > $b[$orderColumn] ? -1 : 1);
});

$noResults = empty($results);
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
    <link rel="stylesheet" href="style/search.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!--  SET SEARCH VALUES  -->
    <script src="scripts/search-set.js" type="text/javascript"></script>
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Voso</title>
</head>

<body>
    <div class="wrapper">
        <?php require_once 'components/left-menu.php'; ?>
        <div class="right-pane">
            <?php require_once 'components/top-menu.php'; ?>
            <div class="main flex-column">
                <h1 class='search-summary'>Results for <span style="color: var(--accent1);"><?= !empty($_GET['q']) ? htmlspecialchars($_GET['q'])."</span>" : "all</span> " ?><?= !empty($_GET['cat']) ? "in genre ".htmlspecialchars($_GET['cat']) : "" ?></span></h1>
                <?php
                $noResults = true;
                $search = !empty($_GET['q']) ? $con->real_escape_string($_GET['q']) : null;
                $type = $_GET['type'] ?? 'default';
                $genre = $_GET['genre'] ?? 'default';
                $sort = $_GET['sort'] ?? 'newest';

                $typeCondition = ($type === 'default') ? "%" : "%{$type}%";
                $genreCondition = ($genre === 'default') ? "%" : "%{$genre}%";

                $orderColumn = '';
                $orderDirection = '';

                if ($sort === 'newest') {
                    $orderColumn = 'brd-start';
                    $orderDirection = 'DESC';
                } elseif ($sort === 'oldest') {
                    $orderColumn = 'brd-start';
                    $orderDirection = 'ASC';
                } elseif ($sort === 'name_asc') {
                    $orderColumn = 'title';
                    $orderDirection = 'ASC';
                } elseif ($sort === 'name_desc') {
                    $orderColumn = 'title';
                    $orderDirection = 'DESC';
                } elseif ($sort === 'default') {
                    $orderColumn = 'id';
                    $orderDirection = 'ASC';
                }
                $sql = "SELECT *
                        FROM series
                        WHERE `isActive` = 1
                        AND `brd-type` LIKE ?
                        AND `genre` LIKE ?";

                $stmt = $con->prepare($sql);
                $stmt->bind_param("ss", $typeCondition, $genreCondition);
                $stmt->execute();
                $result = $stmt->get_result();
                $results = $result->fetch_all(MYSQLI_ASSOC);
                $series = array();
                foreach ($results as $row) {
                    $series[] = array(
                        'alt_title' => $row["alt_title"],
                        'title' => $row["title"],
                        'tags' => $row["tags"],
                        'id' => $row["id"],
                        'poster' => $row["poster"],
                        'season' => $row["season"],
                        'brd-start' => $row["brd-start"],
                        'brd-type' => $row["brd-type"]
                    );
                }
                $options = array(
                    'keys' => array('alt_title', 'title', 'tags'),
                    'threshold' => 0.3
                );

                $fuse = new Fuse\Fuse($series, $options);

                $results = [];
                foreach ($series as $item) {
                    $results[] = [
                        "item" => $item,
                        "refIndex" => count($series),
                    ];
                }
                if ($search !== null) {
                    $results = $fuse->search($search);
                }
                usort($results, function($a, $b) use ($orderColumn, $orderDirection) {
                    if ($a['item'][$orderColumn] === $b['item'][$orderColumn]) {
                        return 0;
                    }

                    if ($orderDirection === 'ASC') {
                        return ($a['item'][$orderColumn] < $b['item'][$orderColumn]) ? -1 : 1;
                    } else {
                        return ($a['item'][$orderColumn] > $b['item'][$orderColumn]) ? -1 : 1;
                    }
                });

                if (!empty($results)) {
                    $noResults = false;
                    echo "<h2>Series:</h2><div class='search-section-series'>";
                    foreach ($results as $result) {
                        $brdType = strtolower($result['item']['brd-type']);
                        if ($brdType === 'series') {
                            $badgeText = "Season {$result['item']['season']}";
                        } elseif ($brdType === 'movie') {
                            $badgeText = "Movie";
                        }
                        echo "
                        <a href='series?s={$result['item']['id']}'>
                            <div class='search-item'>
                                <img src='{$result['item']['poster']}'>
                                <span class='badge'>$badgeText</span>
                                <div class='item-data'>
                                    <p>{$result['item']['title']}</p>
                                </div>
                            </div>
                        </a>";
                    }
                    echo "</div>";
                }

                if ($noResults) {
                    echo "<div class='noResults flex flex-row v-mid h-mid'>";
                    echo "<p>No results</p>";
                    echo "</div>";
                }
                ?>
            </div>
            <?php require_once 'components/footer.php'; ?>
        </div>
    </div>
</body>

</html>
<?php $con->close() ?>