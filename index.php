<?php
require('config.php');
session_start();
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
	<link rel="shortcut icon" href="public/images/logo.png" type="image/x-icon">
</head>

<body>
	<?php require('components/header_navigation_bar.php'); ?>

	<section class="hero">
		<div class="hero-image-container"></div>

		<div class="hero-text-container">
			<img src="public/images/logo.png" alt="logo" />
			<h1>A gallery of <br> memorable taste</h1>
			<p>A place where friends gather, families celebrate, and visitors get a taste of Sri Lanka's rich cultural heritage.</p>

			<a class="btn-primary join-us-btn" href="./pages/auth/register.php">Join With Us</a>
		</div>

	</section>

	<section class="our-speciality-container">
		<img src="<?php echo BASE_URL; ?>/public/images/gallery-cafe-interior-1.jpg" alt="The Gallery Café Interior">
		<div class="info-text-container">
			<h3>What's special about us ?</h3>
			<p>The Gallery Café is a renowned restaurant located in the heart of Colombo, celebrated for its exquisite ambiance and culinary excellence. As one of the city's most iconic dining destinations, The Gallery Café is dedicated to providing exceptional dining experiences with a focus on quality and creativity.</p>
			<p>Known for its unique blend of local and international cuisines, the café has become a favorite among food enthusiasts. With a commitment to excellence, The Gallery Café continues to expand its offerings, delighting guests with memorable meals and impeccable service.</p>

			<div class="images-container">

				<img src="public/images/gallery-cafe-interior-2.jpg" alt="The Gallery Cafe Interior">

				<img src="public/images/barista-making-a-coffee.jpg" alt="A barista making coffee">

				<img src="public/images/gallery-cafe-exterior-1.jpg" alt="The Gallery Cafe Exterior">

				<!-- <img src="public/images/gallery-cafe-interior-4.jpg" alt="The Gallery Cafe Interior"> -->
			</div>
		</div>

	</section>

	<section class="featured-menus-container">
		<div class="featured-menu-info">
			<h3>Featured Menus</h3>
			<p>Whether you're in the mood for a hearty breakfast, a leisurely lunch, or an exquisite dinner, our featured menus offer something to satisfy every palate. Join us and savor the exceptional cuisine that defines The Gallery Café experience.</p>
		</div>
		<div class="featured-menus">

			<?php require('components/menu_item.php');

			for ($i = 0; $i < 10; $i++) {
				echo renderMenuItem('Black Cookie Latte', '1200.00', BASE_URL . '/public/images/black-cookie-latte.jpg');
			}
			?>
	</section>

	<?php require('components/footer.php'); ?>
</body>

</html>
