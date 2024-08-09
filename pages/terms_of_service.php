<?php
require('../config.php');
require('../utils/database.php');
session_start();

$conn = initialize_database();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Terms of Service - The Gallery Cafe</title>
    <link rel="stylesheet" href="../public/styles/styles.css">
    <link rel="stylesheet" href="../public/styles/fonts.css">
    <link rel="stylesheet" href="../public/styles/legal.css">
    <link rel="shortcut icon" href="../public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../components/header_navigation_bar.php'); ?>

    <main class="">
        <section class="terms-of-service">
            <div class="terms-of-service-info">
                <h1>Terms of Service</h1>
                <p>These Terms of Service outline the rules and regulations for using our services. By accessing or using our website, you agree to comply with and be bound by these terms. Please read them carefully to understand your rights and obligations while engaging with our platform.</p>
            </div>
            <div class="terms">
                <div class="term">
                    <h3>Introduction</h3>
                    <p>Welcome to our restaurant's website. These terms of service govern your use of our site and services. By using our site, you agree to these terms.</p>
                </div>

                <div class="term">
                    <h3>Account Registration</h3>
                    <p>To use certain features of our site, you may need to create an account. You agree to provide accurate and complete information and to keep this information up to date. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account.</p>
                </div>

                <div class="term">
                    <h3>Use of Our Services</h3>
                    <p>You agree to use our services only for lawful purposes and in accordance with these terms. You agree not to use our services in any way that could damage, disable, or impair our site or interfere with any other party's use of our services.</p>
                </div>

                <div class="term">
                    <h3>Reservations and Orders</h3>
                    <p>When you make a reservation or place an order, you agree to provide accurate information. We reserve the right to refuse or cancel any reservation or order at our discretion.</p>
                </div>

                <div class="term">
                    <h3>Payment</h3>
                    <p>All payments must be made at the time of ordering. We accept various forms of payment, which are listed on our site. Prices are subject to change without notice.</p>
                </div>

                <div class="term">
                    <h3>Limitation of Liability</h3>
                    <p>We are not liable for any indirect, incidental, special, or consequential damages arising out of or in connection with your use of our services. Our total liability to you for any claims arising from your use of our services is limited to the amount you paid us for the services in question.</p>
                </div>

                <div class="term">
                    <h3>Changes to These Terms</h3>
                    <p>We may update these terms of service from time to time. We will notify you of any changes by posting the new terms on our website. You are advised to review these terms periodically for any changes.</p>
                </div>

                <div class="term">
                    <h3>Contact Us</h3>
                    <p>If you have any questions about these terms of service, please contact us at <a href="mailto:info@thegallerycafe.com">info@thegallerycafe.com</a>.</p>
                </div>
            </div>
        </section>

    </main>
    <?php include('../components/footer.php'); ?>
</body>

</html>
