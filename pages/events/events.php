<?php
require('../../config.php');
require('../../utils/database.php');
session_start();

$conn = initialize_database();

$sql = "SELECT * FROM `event` WHERE `ends_at` > NOW() AND `deleted_at` IS NULL;";
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
        <section class="special-events">
            <div class="special-events-info">
                <h1>Upcoming Special Events</h1>
                <p>Join us for a series of exciting special events at our restaurant! From themed dinners and live music nights to seasonal celebrations and exclusive tasting menus, there's always something happening. Mark your calendars and be sure to reserve your spot for an unforgettable dining experience.</p>
            </div>
            <div class="events-container">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $starts_at = date("Y M d \a\\t H:i A", strtotime($row['starts_at']));

                    echo <<< HTML
                    <div class="event">
                        <span class="event-icon material-symbols-rounded">event</span>
                        <h2 class="event-name">{$row['name']}</h2>
                        <p class="event-description">{$row['description']}</p>
                        <div class="event-duration">
                            <span class="material-symbols-rounded btn-icon">schedule</span> 
                            <span>{$starts_at}</span>
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
