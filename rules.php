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
                                <div class="inner-text">
                                    <h1>Terms Of Service</h1>

                                    <h3>1. Tos Point</h3>
                                    <p>
                                        1.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        1.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        1.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>

                                    <h3>2. Tos Point</h3>
                                    <p>
                                        2.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        2.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        2.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>

                                    <h3>3. Tos Point</h3>
                                    <p>
                                        3.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        3.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        3.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>

                                    <h3>4. Tos Point</h3>
                                    <p>
                                        4.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        4.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        4.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>

                                    <h3>5. Tos Point</h3>
                                    <p>
                                        5.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        5.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        5.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>

                                    <h3>6. Tos Point</h3>
                                    <p>
                                        6.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        6.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        6.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>

                                    <h3>7. Tos Point</h3>
                                    <p>
                                        7.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        7.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        7.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>

                                    <h3>8. Tos Point</h3>
                                    <p>
                                        8.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        8.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        8.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>

                                    <h3>9. Tos Point</h3>
                                    <p>
                                        9.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        9.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        9.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>

                                    <h3>10. Tos Point</h3>
                                    <p>
                                        10.1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        10.2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                        10.3. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                                    </p>
                                    <br><br>
                                    <p>Terms Of Service updated 01.01.2024 r.</p>
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
                        <li><a class="active-footer" href="/rules">Terms Of Service</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <li><a href="mailto:voso@voso.com">Email us!</a></li>
                    </ul>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>