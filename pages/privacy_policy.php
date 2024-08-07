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
    <title>Privacy Policy - The Gallery Cafe</title>
    <link rel="stylesheet" href="../public/styles/styles.css">
    <link rel="stylesheet" href="../public/styles/fonts.css">
    <link rel="stylesheet" href="../public/styles/legal.css">
    <link rel="shortcut icon" href="../public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../components/header_navigation_bar.php'); ?>

    <main class="">
        <section class="privacy-policy">
            <div class="privacy-policy-info">
                <h1>Privacy Policy</h1>
                <p>We value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, and safeguard your data when you visit our website or use our services. Please take a moment to read through this policy to understand our practices and how we ensure the confidentiality and security of your information.</p>
            </div>
            <div class="policies">
                <div class="policy">
                    <h3>Introduction</h3>
                    <p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information.</p>
                </div>
                <div class="policy">
                    <h3>Information We Collect</h3>
                    <p>We collect information that you provide to us directly, such as when you create an account, make a reservation, or contact us. This may include your name, email address, phone number, and payment information.</p>
                    <p>We also collect information automatically, such as through cookies and other tracking technologies. This includes your IP address, browser type, and browsing behavior on our site.</p>
                </div>
                <div class="policy">
                    <h3>How We Use Your Information</h3>
                    <p>We use your information to provide and improve our services, process transactions, and communicate with you. We may also use your information for marketing purposes, with your consent.</p>
                </div>
                <div class="policy">
                    <h3>Sharing Your Information</h3>
                    <p>We do not sell your personal information. We may share your information with third parties to provide our services, comply with legal obligations, or protect our rights.</p>
                </div>
                <div class="policy">
                    <h3>Your Rights</h3>
                    <p>You have the right to access, correct, or delete your personal information. You can also object to or restrict our processing of your information. To exercise these rights, please contact us.</p>
                </div>
                <div class="policy">
                    <h3>Changes to This Policy</h3>
                    <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on our website. You are advised to review this policy periodically for any changes.</p>
                </div>
                <div class="policy">
                    <h3>Contact Us</h3>
                    <p>If you have any questions about this privacy policy, please contact us at <a href="mailto:info@thegallerycafe.com">info@thegallerycafe.com</a>.</p>
                </div>
            </div>
        </section>

    </main>
    <?php include('../components/footer.php'); ?>
</body>

</html>
