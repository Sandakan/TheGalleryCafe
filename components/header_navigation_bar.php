<?php
function getFullUrl()
{
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$host = $_SERVER['HTTP_HOST'];
	$requestUri = $_SERVER['REQUEST_URI'];

	return $protocol . $host . $requestUri;
}

function isActivePage($url)
{
	if (strpos(getFullUrl(), $url) !== false) echo 'active';
	else echo 'not-active';
}
?>

<nav class="blurred-background">
	<a href="<?php echo BASE_URL; ?>/index.php" class="logo-container">
		<img src="<?php echo BASE_URL; ?>/public/images/logo.png" alt="logo" />
		<h1>The Gallery Café</h1>
	</a>


	<ul class="nav-links">
		<li class="<?php isActivePage('/index.php') ?>"><a href="<?php echo BASE_URL; ?>/index.php">Home</a></li>
		<li class="<?php isActivePage('/pages/menu/menu.php') ?>"><a href="<?php echo BASE_URL; ?>/pages/menu/menu.php">Menu</a></li>
		<li class="<?php isActivePage('#about') ?>"><a href="#about">About</a></li>
		<li class="<?php isActivePage('#contact') ?>"><a href="#contact">Contact</a></li>
		<li class="<?php isActivePage('/pages/cart/cart.php') ?>"><a href="<?php echo BASE_URL; ?>/pages/cart/cart.php"><span class="material-symbols-rounded">
					shopping_cart
				</span></a></li>
		<li class="">
			<?php if (isset($_SESSION['user_id'])) : ?>
				<a class="logged-user-btn" href="<?php echo BASE_URL; ?>/pages/profile/profile.php"><span class="material-symbols-rounded material-symbols-rounded-filled">
						account_circle
					</span> <span><?php echo $_SESSION['user_first_name']; ?></span></a>
			<?php else : ?>
				<a class="login-register-btn" href="<?php echo BASE_URL; ?>/pages/auth/login.php">Login / Signup</a>
			<?php endif; ?>
		</li>
	</ul>
</nav>
