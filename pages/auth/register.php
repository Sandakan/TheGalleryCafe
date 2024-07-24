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

    <div class="model-container register-model-container">
        <div class="model register-model">
            <?php include('../../components/user_account_benefits.php'); ?>
            <form class="register-form" action="">
                <h1>Join with us...</h1>

                <div class="input-group-container">
                    <div class="input-container">
                        <label for="first_name">First Name *</label>
                        <input type="text" name="first_name" id="first_name" placeholder="John" required />
                    </div>

                    <div class="input-container">
                        <label for="last_name">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Doe" required />
                    </div>
                </div>

                <div class="input-container">
                    <label for="email">Email *</label>
                    <input type="email" name="email" id="email" placeholder="johndoe@example.com" required />
                </div>

                <div class="input-container">
                    <label for="contact_number">Contact Number *</label>
                    <input type="tel" name="contact_number" id="contact_number" placeholder="+94 712 345 678" required />
                </div>

                <div class="input-container">
                    <label for="password">Password *</label>
                    <input type="password" name="password" id="password" placeholder="MySuperSecretPassword" required />
                </div>

                <div class="input-container">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="MySuperSecretPassword" required />
                </div>

                <button class="btn-primary form-submit-btn" type="submit">Register</button>

                <div class="create-account-link-container"><a href="./login.php">Already a member? Then, login to your account.</a></div>
            </form>
        </div>

    </div>

    <?php include('../../components/footer.php'); ?>
</body>

</html>
