<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['loggedin'])) {
    header('Location: browse');
    exit;
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
    <link rel="stylesheet" href="https://vjs.zencdn.net/7.21.1/video-js.css">
    <link rel="stylesheet" href="style/auth.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- NOTY.JS -->
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <!-- FORM VALIDATOR -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- TITLE AND FAVICON -->
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Voso</title>
</head>

<body>
    <div class="wrapper vh100 v-mid h-mid">
        <div class="auth-card">
            <div class="auth-card-left">
                <a href="/browse"><img src="logo/logo.png" draggable="false" alt="logo"></a>
            </div>
            <div class="auth-card-right">
                <h1>Sign in</h1>
                <form method="post" action="scripts/authenticate-log" id="form-login">
                    <div class="form-container">
                        <label for="username">Email</label>
                        <input type="text" name="username" placeholder="Email" class="username" required>
                    </div>
                    <div class="form-container">
                        <label for="password">Password</label>
                        <input type="password" name="password" placeholder="Password" class="password" required>
                    </div>
                    <div class="remember-me">
                        <input type="checkbox" name="remember" class="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <div class="trd-login">
                        <input type="submit" value="Sign in">
                    </div>
                    <div class="switch-auth"><a href="/register">No account? Sign up</a></div>
                </form>
                <script src="scripts/login-validate.js"></script>
            </div>
        </div>
    </div>
</body>

</html>
<?php $con->close() ?>