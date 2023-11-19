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
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- STYLES -->
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/icons/fontawesome/css/all.min.css">
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="/logo/favicon.png" />
    <title>Voso</title>
</head>
    <body>
        <div class="wrapper">
            <?php require_once 'components/left-menu.php'; ?>
            <div class="right-pane">
                <?php require_once 'components/top-menu.php'; ?>
                <div class="main flex flex-column error-page" style="min-height: 88vh;">
                    <h1 class="error-text">403</h1>
                    <span class="error-text">Forbidden</span>
                </div>
                <?php require_once 'components/footer.php'; ?>
            </div>
        </div>
    </body>
</html>
<?php $con->close() ?>