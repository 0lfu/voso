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
                                    <h1>Terms of Service</h1>

                                    <h3>0. Introduction</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

                                    <h3>1. General Information</h3>
                                    <p>Our website (hereinafter referred to as the "Service") respects the privacy of its users and ensures the protection of personal data. This Privacy Policy describes the types of personal data collected by the Service, how they are processed, and the rights users have regarding the processing of their personal data.</p>

                                    <h3>2. Purposes and Legal Bases for Processing Personal Data</h3>
                                    <ol type="a">
                                        <li>2.1. Purposes of processing personal data by the Administrator:
                                            <ul>
                                                <li>Facilitating user access to the Service;</li>
                                                <li>Ensuring the security and protection of Service users;</li>
                                                <li>Providing electronic services and managing user accounts;</li>
                                                <li>Potentially pursuing claims and defending against claims;</li>
                                                <li>Direct marketing.</li>
                                            </ul>
                                        </li>
                                        <li>2.2. Legal bases for processing personal data by the Administrator:
                                            <ul>
                                                <li>Processing personal data is necessary for the performance of a contract with the user of the Service or for taking pre-contractual steps at the user's request;</li>
                                                <li>Processing personal data is necessary to fulfill obligations arising from legal provisions;</li>
                                                <li>Processing personal data is necessary for purposes arising from the Administrator's legitimate interests, especially direct marketing.</li>
                                            </ul>
                                        </li>
                                    </ol>

                                    <h3>3. Categories of Processed Personal Data</h3>
                                    <ol type="a">
                                        <li>3.1. When using the Service, the Administrator processes the following categories of personal data:
                                            <ul>
                                                <li>Identification data, including email address and password;</li>
                                                <li>Login data, including IP address, time of using the Service, and type of internet browser;</li>
                                                <li>Data obtained as a result of using the Service, including information about viewed content.</li>
                                            </ul>
                                        </li>
                                    </ol>

                                    <h3>4. Recipients of Personal Data</h3>
                                    <ol>
                                        <li>4.1. Personal data of users of the Service may be transferred to other entities only when necessary to achieve the purposes specified in this Privacy Policy and in cases required by law.</li>
                                        <li>4.2. Personal data will not be transferred outside the European Economic Area or to third countries.</li>
                                    </ol>

                                    <h3>5. Data Retention Period</h3>
                                    <ol>
                                        <li>5.1. Personal data will be processed by the Administrator for the period necessary to achieve the purposes for which they were collected.</li>
                                        <li>5.2. In the case of creating an account by providing an email and password, this data will be stored until the user deletes the account or withdraws consent for the processing of personal data.</li>
                                        <li>5.3. In the case of creating an account through Google or Facebook, we do not store any password.</li>
                                        <li>5.4. Login data will be stored for the period necessary to ensure the security of the Service and for statistical and archival purposes.</li>
                                        <li>5.5. Data obtained as a result of using the Service will be stored for the period necessary to provide electronic services and for statistical and archival purposes.</li>
                                    </ol>

                                    <h3>6. User Rights</h3>
                                    <ol>
                                        <li>6.1. Users of the Service have the following rights related to the processing of their personal data:
                                            <ul>
                                                <li>Right to access their personal data and obtain a copy of it;</li>
                                                <li>Right to correct their personal data;</li>
                                                <li>Right to delete their personal data;</li>
                                                <li>Right to restrict the processing of their personal data;</li>
                                                <li>Right to transfer their personal data;</li>
                                                <li>Right to object to the processing of their personal data;</li>
                                                <li>Right to withdraw consent for the processing of their personal data.</li>
                                            </ul>
                                        </li>
                                        <li>6.2. To exercise the above rights, users should contact the Administrator via email: contact@example.com.</li>
                                    </ol>

                                    <h3>7. Principles of Personal Data Protection</h3>
                                    <ol type="a">
                                        <li>7.1. The Administrator employs appropriate technical and organizational measures to ensure the protection of personal data against unauthorized access, loss, destruction, or damage.</li>
                                        <li>7.2. To ensure the security of the Service, the Administrator uses technical and organizational measures such as:
                                            <ul>
                                                <li>Encryption of the connection to the Service using the SSL/TLS protocol;</li>
                                                <li>Implementation of safeguards to prevent unauthorized access to personal data;</li>
                                                <li>Control of access to personal data by authorized persons.</li>
                                            </ul>
                                        </li>
                                    </ol>

                                    <h3>8. Changes to the Terms of Service</h3>
                                    <ol>
                                        <li>8.1. The Administrator reserves the right to make changes to the Terms of Service. These changes will be published on the Service's website.</li>
                                        <li>8.2. In the case of significant changes to the Terms of Service, the Administrator will inform users of the Service via email or through information on the main page of the Service.</li>
                                    </ol>

                                    <h3>9. Contact</h3>
                                    <ol>
                                        <li>9.1. For questions, concerns, or comments regarding the Terms of Service, users can contact the Administrator via email: contact@example.com.</li>
                                        <li>9.2. The Service Administration reserves the right not to respond to inquiries regarding the Terms of Service if they are not sent through the specified communication platform.</li>
                                    </ol>

                                    <p>The Terms of Service were last updated on July 23, 2023.</p>
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
                        <li><a class="active-footer" href="/policy">Privacy policy</a></li>
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