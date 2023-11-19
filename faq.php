<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/icons/fontawesome/css/all.min.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
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
                <div class="outter-container">
                    <section class="showcase">
                        <div class="showcase-container">
                            <div class="inner-container">
                                <div class="inner-text faq">
                                    <h1>FAQ - Frequently Asked Questions</h1>
                                    <h3>Question number one?</h3>
                                    <p>- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores commodi deserunt excepturi minus officiis, perferendis? Architecto cum dicta et, expedita harum id iusto nam qui sapiente similique sit suscipit voluptate.</p>
                                    <h3>Question number two?</h3>
                                    <p>- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores commodi deserunt excepturi minus officiis, perferendis? Architecto cum dicta et, expedita harum id iusto nam qui sapiente similique sit suscipit voluptate.</p>
                                    <h3>Question number three?</h3>
                                    <p>- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores commodi deserunt excepturi minus officiis, perferendis? Architecto cum dicta et, expedita harum id iusto nam qui sapiente similique sit suscipit voluptate.</p>
                                    <h3>Question number four?</h3>
                                    <p>- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores commodi deserunt excepturi minus officiis, perferendis? Architecto cum dicta et, expedita harum id iusto nam qui sapiente similique sit suscipit voluptate.</p>
                                    <h3>Question number five?</h3>
                                    <p>- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores commodi deserunt excepturi minus officiis, perferendis? Architecto cum dicta et, expedita harum id iusto nam qui sapiente similique sit suscipit voluptate.</p>
                                    <h3>Question number six?</h3>
                                    <p>- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores commodi deserunt excepturi minus officiis, perferendis? Architecto cum dicta et, expedita harum id iusto nam qui sapiente similique sit suscipit voluptate.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <footer>
                <p>Copyright © $year voso. All rights reserved.</p>
                <p class="additional-info">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi doloribus eaque fugit ipsam ipsum iusto mollitia natus nesciunt non optio pariatur perferendis qui quod sit soluta sunt suscipit, tenetur voluptas?</p>
                <div class="links">
                    <ul>
                        <li><a href="/policy">Privacy policy</a></li>
                        <li><a href="/rules">Terms Of Service</a></li>
                        <li><a class="active-footer" href="/faq">FAQ</a></li>
                        <li><a href="mailto:voso@voso.com">Email us!</a></li>
                    </ul>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>