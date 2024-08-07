<?php
require('config.php');
require('./utils/database.php');
session_start();

$conn = initialize_database();

// handle GET request to logout
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($_GET['logged_out']) && $_GET['logged_out'] == 'true') {
		session_destroy();
		header('Location: ' . BASE_URL . '/index.php');
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Home - The Gallery Cafe</title>
	<link rel="stylesheet" href="public/styles/styles.css">
	<link rel="stylesheet" href="public/styles/fonts.css">
	<link rel="stylesheet" href="public/styles/home.css">
	<link rel="stylesheet" href="public/styles/menu.css">
	<link rel="stylesheet" href="public/styles/events_and_promotions.css">
	<link rel="shortcut icon" href="public/images/logo.webp" type="image/x-icon">
</head>

<body>
	<?php require('components/header_navigation_bar.php'); ?>

	<section class="hero index-hero">
		<div class="hero-image-container"></div>
		<div class="hero-text-container">
			<img src="public/images/logo.webp" alt="logo" />
			<h1>A gallery of <br> memorable taste</h1>
			<p>A place where friends gather, families celebrate, and visitors get a taste of Sri Lanka's rich cultural heritage.</p>
			<a class="btn-primary join-us-btn" href="./pages/auth/register.php">Join With Us</a>
		</div>
	</section>

	<main>
		<section class="our-speciality-container">
			<img src="<?php echo BASE_URL; ?>/public/images/gallery-cafe-interior-1.webp" alt="The Gallery Café Interior">
			<div class="info-text-container">
				<h3>What's special about us ?</h3>
				<p>The Gallery Café is a renowned restaurant located in the heart of Colombo, celebrated for its exquisite ambiance and culinary excellence. As one of the city's most iconic dining destinations, The Gallery Café is dedicated to providing exceptional dining experiences with a focus on quality and creativity.</p>
				<p>Known for its unique blend of local and international cuisines, the café has become a favorite among food enthusiasts. With a commitment to excellence, The Gallery Café continues to expand its offerings, delighting guests with memorable meals and impeccable service.</p>
				<div class="images-container">
					<img src="public/images/gallery-cafe-interior-2.webp" alt="The Gallery Cafe Interior">
					<img src="public/images/barista-making-a-coffee.webp" alt="A barista making coffee">
					<img src="public/images/gallery-cafe-exterior-1.webp" alt="The Gallery Cafe Exterior">
					<!-- <img src="public/images/gallery-cafe-interior-4.webp" alt="The Gallery Cafe Interior"> -->
				</div>
			</div>
		</section>

		<section class="featured-menus-container">
			<div class="featured-menu-info">
				<h3>Featured Menus</h3>
				<p>Whether you're in the mood for a hearty breakfast, a leisurely lunch, or an exquisite dinner, our featured menus offer something to satisfy every palate. Join us and savor the exceptional cuisine that defines The Gallery Café experience.</p>
			</div>
			<div class="featured-menus menus">
				<?php require('components/menu_item.php');
				$sql = "SELECT id, name, description, price, image FROM menu_item WHERE menu_item.type = 'SPECIAL'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) {
					// Loop through each result and display in table rows
					while ($row = mysqli_fetch_assoc($result)) {
						echo renderMenuItem(intval($row['id']), $row['name'], $row['price'], BASE_URL . '/public/images/menu-items/' . $row['image']);
					}
				}
				?>
			</div>
		</section>

		<section class="promotions">
			<div class="promotions-info">
				<h1>Current Promotions</h1>
				<p>Don't miss out on our exclusive promotions! Enjoy special discounts, limited-time offers, and exciting deals on your favorite dishes and beverages. Check back often to take advantage of these fantastic savings and enhance your dining experience with us.</p>
			</div>
			<div class="promotions-container">
				<?php

				$sql = "SELECT * FROM `promotion` WHERE `ends_at` > NOW() AND `deleted_at` IS NULL;";
				$result = mysqli_query($conn, $sql);

				while ($row = mysqli_fetch_assoc($result)) {
					$starts_at = date("Y M d \a\\t g:i A", strtotime($row['starts_at']));
					$ends_at = date("Y M d \a\\t g:i A", strtotime($row['ends_at']));

					echo <<< HTML
                    <div class="promotion">
                        <span class="promotion-icon material-symbols-rounded">sell</span>
                        <h2 class="promotion-name">{$row['name']}</h2>
                        <p class="promotion-description">{$row['description']}</p>
                        <div class="promotion-duration">
                            <div>
                                <span>From {$starts_at}</span>
                            </div>
                            <div>
                                <span>To {$ends_at}</span>
                            </div>
                        </div>
                    </div>
                    HTML;
				}
				?>
			</div>
		</section>
	</main>

	<?php require('components/footer.php'); ?>
</body>

</html>
