<?php include '../../config.php'; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.png" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <div class="model-container login-model-container">
        <div class="model login-model">
            <?php include('../../components/user_account_benefits.php'); ?>
            <form class="login-form" action="">
                <h1>Welcome back...</h1>

                <div class="input-container">
                    <label for="email">Email *</label>
                    <input type="email" name="email" id="email" placeholder="johndoe@example.com" required />
                </div>

                <div class="input-container">
                    <label for="password">Password *</label>
                    <input type="password" name="password" id="password" placeholder="MySuperSecretPassword" required />
                    <div class="forget-password-link-container"><a href="./forgot-password.php">Did you forget your password?</a></div>
                </div>

                <button class="btn-primary" type="submit">Log In</button>

                <div class="create-account-link-container"><a href="./register.php">Not a member? Then, join with us.</a></div>
            </form>
        </div>

    </div>

    <?php include('../../components/footer.php'); ?>
</body>

</html>
