<?php
require('../../config.php');
require('../../utils/database.php');
session_start();

$conn = initialize_database();


$sql = "SELECT * FROM `promotion`";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Promotions - The Gallery Cafe</title>
    <link rel="stylesheet" href="../../public/styles/styles.css">
    <link rel="stylesheet" href="../../public/styles/fonts.css">
    <link rel="stylesheet" href="../../public/styles/events_and_promotions.css">
    <link rel="shortcut icon" href="../../public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>
    <main>
        <section class="promotions">
            <div class="promotions-info">
                <h1>Current Promotions</h1>
                <p>Don't miss out on our exclusive promotions! Enjoy special discounts, limited-time offers, and exciting deals on your favorite dishes and beverages. Check back often to take advantage of these fantastic savings and enhance your dining experience with us.</p>
            </div>
            <div class="promotions-container">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $starts_at = date("Y M d \a\\t H:i A", strtotime($row['starts_at']));
                    $ends_at = date("Y M d \a\\t H:i A", strtotime($row['ends_at']));

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
                                <span>To {$starts_at}</span>
                            </div>
                        </div>
                    </div>
                    HTML;
                }
                ?>
            </div>
        </section>
    </main>

    <?php include('../../components/footer.php'); ?>
</body>

</html>
