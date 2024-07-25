<?php
function getFullUrl()
{
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$host = $_SERVER['HTTP_HOST'];
	$requestUri = $_SERVER['REQUEST_URI'];

	return $protocol . $host . $requestUri;
}

// echo getFullUrl();
?>

<nav class="blurred-background">
	<a href="<?php echo BASE_URL; ?>/index.php" class="logo-container">
		<img src="<?php echo BASE_URL; ?>/public/images/logo.png" alt="logo" />
		<h1>The Gallery Café</h1>
	</a>


	<ul class="nav-links">
		<li class="active"><a href="<?php echo BASE_URL; ?>/index.php">Home</a></li>
		<li><a href="#menu">Menu</a></li>
		<li><a href="#about">About</a></li>
		<li><a href="#contact">Contact</a></li>
		<!-- <li><a href="#cart"><span class="material-symbols-rounded">
					shopping_cart
				</span></a></li> -->
		<li>
			<?php if (isset($_SESSION['user_id'])) : ?>
				<a class="logged-user-btn" href="<?php echo BASE_URL; ?>/pages/auth/login.php"><span class="material-symbols-rounded material-symbols-rounded-filled">
						account_circle
					</span> <span><?php echo $_SESSION['user_first_name']; ?></span></a>
			<?php else : ?>
				<a class="login-register-btn" href="<?php echo BASE_URL; ?>/pages/auth/login.php">Login/Register</a>
			<?php endif; ?>
		</li>

	</ul>
</nav>
