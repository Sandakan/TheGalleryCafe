<?php
require '../../config.php';
require '../../utils/database.php';
require '../../components/menu_item.php';

$conn = initialize_database();
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= $_SESSION['user_first_name'] . '\'s Profile - ' ?>The Gallery Café</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/profile.css">
    <link rel="shortcut icon" href="<?= BASE_URL ?>/public/images/logo.png" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>


    <div class="model-container menu-item-container-model">
        <div class="model menu-item-model">
           
        </div>
    </div>

    <?php include('../../components/footer.php'); ?>
</body>

</html>