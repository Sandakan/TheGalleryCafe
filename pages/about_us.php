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
    <title>About Us - The Gallery Cafe</title>
    <link rel="stylesheet" href="../public/styles/styles.css">
    <link rel="stylesheet" href="../public/styles/fonts.css">
    <link rel="stylesheet" href="../public/styles/home.css">
    <link rel="stylesheet" href="../public/styles/menu.css">
    <link rel="shortcut icon" href="../public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../components/header_navigation_bar.php'); ?>

    <section class="hero about-us-hero">
        <div class="hero-image-container"></div>
        <div class="hero-text-container">
            <h1>About Us</h1>
            <p>Get to know what's special about us and the secret behind our talent.</p>
        </div>
    </section>

    <main>
        <section class="our-speciality-container">
            <img src="<?= BASE_URL; ?>/public/images/gallery-cafe-interior-1.webp" alt="The Gallery Café Interior">
            <div class="info-text-container">
                <h3>What's special about us ?</h3>
                <p>The Gallery Café is a renowned restaurant located in the heart of Colombo, celebrated for its exquisite ambiance and culinary excellence. As one of the city's most iconic dining destinations, The Gallery Café is dedicated to providing exceptional dining experiences with a focus on quality and creativity.</p>
                <p>Known for its unique blend of local and international cuisines, the café has become a favorite among food enthusiasts. With a commitment to excellence, The Gallery Café continues to expand its offerings, delighting guests with memorable meals and impeccable service.</p>
                <div class="images-container">
                    <img src="<?= BASE_URL; ?>/public/images/gallery-cafe-interior-2.webp" alt="The Gallery Cafe Interior">
                    <img src="<?= BASE_URL; ?>/public/images/barista-making-a-coffee.webp" alt="A barista making coffee">
                    <img src="<?= BASE_URL; ?>/public/images/gallery-cafe-exterior-1.webp" alt="The Gallery Cafe Exterior">
                    <!-- <img src="<?= BASE_URL; ?>/public/images/gallery-cafe-interior-4.webp" alt="The Gallery Cafe Interior"> -->
                </div>
            </div>
        </section>

        <!-- <h1>About Us</h1>
        <p>Welcome to [Your Café Name], a cozy spot where you can enjoy the finest coffee and delicious pastries. We pride ourselves on using the freshest ingredients and providing a warm, welcoming atmosphere for all our guests.</p>

        <h2>Our Story</h2>
        <p>[Your Café Name] was founded in [Year] by [Founder's Name]. With a passion for coffee and a dream to create a community hub, [Founder's Name] opened the doors to our café in [Location]. Over the years, we've grown and evolved, but our commitment to quality and customer satisfaction remains the same.</p>
         -->
        <section class="meet-our-team">
            <div class="meet-our-team-info-container">
                <h2>Meet Our Team</h2>
                <p>Our team is comprised of talented individuals who are passionate about creating exceptional dining experiences.</p>
            </div>
            <div class="team-members-container">
                <div class="team-member">
                    <img src="<?= BASE_URL; ?>/public/images/team/team-member-1.jpg" alt="Team Member 1">
                    <h3>Jane Doe</h3>
                    <p>Founder & CEO</p>
                    <!-- <p>With a background in culinary arts and a love for coffee, Jane has led our cafe to become a beloved spot in the community.</p> -->
                </div>
                <div class="team-member">
                    <img src="<?= BASE_URL; ?>/public/images/team/team-member-2.jpg" alt="Team Member 2">
                    <h3>John Smith</h3>
                    <p>Head Barista</p>
                    <!-- <p>An expert in coffee brewing, John brings a wealth of knowledge and passion to every cup.</p> -->
                </div>
                <div class="team-member">
                    <img src="<?= BASE_URL; ?>/public/images/team/team-member-3.jpg" alt="Team Member 3">
                    <h3>Emily Johnson</h3>
                    <p>Executive Chef</p>
                    <!-- <p>Our culinary mastermind, Emily, crafts delicious pastries and snacks that perfectly complement our coffee.</p> -->
                </div>
                <div class="team-member">
                    <img src="<?= BASE_URL; ?>/public/images/team/team-member-4.jpg" alt="Team Member 4">
                    <h3>Michael Brown</h3>
                    <p>Cafeteria Manager</p>
                    <!-- <p>Ensuring smooth operations and a fantastic customer experience, Michael is an integral part of our team.</p> -->
                </div>
            </div>
        </section>
    </main>

    <?php include('../components/footer.php'); ?>
</body>

</html>
