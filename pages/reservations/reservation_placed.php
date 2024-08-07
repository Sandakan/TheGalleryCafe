<?php
require '../../config.php';
require '../../utils/database.php';
require '../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate();
$user_id = $_SESSION["user_id"];

if (!isset($_GET["reservation_id"])) {
    echo "Reservation ID not found";
    exit();
}

$reservation_id = $_GET["reservation_id"];

// get reservation info

$sql = <<< SQL
SELECT 
    `reservation`.`id` AS `reservation_id`,
    `reservation`.`no_of_people` AS `no_of_people`,
    `table_reservation`.`starts_at` AS `starts_at`,
    `table_reservation`.`ends_at` AS `ends_at`,
    `table_reservation`.`table_id` AS `table_id`,
    `reservation`.`special_request` AS `special_request`,
    `reservation`.`occasion` AS `occasion`,
    `order`.`id` AS reservation_pre_order_id,
    `order`.`total_amount` AS reservation_pre_order_total_amount
FROM
    `reservation`
INNER JOIN
    `table_reservation` ON `table_reservation`.`id` = `reservation`.`table_reservation_id`
LEFT JOIN 
    `order` ON `order`.`reservation_id` = `reservation`.`id`
WHERE
    `reservation`.`id` = $reservation_id
    AND `reservation`.`deleted_at` IS NULL
    AND `table_reservation`.`deleted_at` IS NULL;
SQL;

$res = mysqli_query($conn, $sql);
$reservation = mysqli_fetch_assoc($res);

if (!$reservation) {
    echo "Reservation not found";
    exit();
}

$reservation_date_and_time = date('F j, Y \a\t g:i A', strtotime($reservation["starts_at"]));
$reservation_no_of_people = $reservation["no_of_people"] . ' ' . ($reservation["no_of_people"] == 1 ? 'person' : 'people');
$reservation_occasion = $reservation["occasion"] ? $reservation["occasion"] : 'N/A';
$reservation_special_request = $reservation["special_request"] ? $reservation["special_request"] : 'N/A';
$reservation_pre_order_id = $reservation["reservation_pre_order_id"];
$reservation_pre_order_total_amount = $reservation["reservation_pre_order_total_amount"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reservation Placed Successfully - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/order_reservation_success.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <main class="">

        <section class="reservation-success">
            <header>
                <span class="material-symbols-rounded-filled">verified</span>
                <h1>Reservation #<?php echo $reservation_id; ?> Placed Successfully.</h1>
            </header>

            <section class="success-details">
                <p>Your reservation has been successfully made. Here are your reservation details:</p>
                <ul>
                    <li><strong>Reservation Number:</strong> #<?= $reservation["reservation_id"]; ?></li>
                    <li><strong>Date and Time:</strong> <?= $reservation_date_and_time; ?></li>
                    <li><strong>Table Number:</strong> #<?= $reservation['table_id']; ?></li>
                    <li><strong>Number of People:</strong> <?= $reservation_no_of_people; ?></li>
                    <li><strong>Occasion:</strong> <?= $reservation_occasion; ?></li>
                    <li><strong>Special Requests:</strong> <?= $reservation_special_request; ?></li>
                </ul>
            </section>

            <?php if ($reservation_pre_order_id) : ?>
                <section class="success-details">
                    <p>Your pre-order items:</p>
                    <ul>
                        <?php
                        $query = <<< SQL
                        SELECT
                            oi.id,
                            mi.name,
                            mi.price,
                            oi.quantity
                        FROM 
                            `order_item` oi
                        INNER JOIN 
                            `menu_item` mi ON mi.id = oi.menu_item_id
                        WHERE
                            oi.order_id = {$reservation_pre_order_id};
                        SQL;

                        $res = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($res)) {
                            $quantity = $row["quantity"];

                            echo <<< HTML
                            <li>{$quantity} x {$row["name"]} - LKR {$row["price"]}</li>
                            HTML;
                        }
                        ?>
                    </ul>

                    <p class="order-total-amount"><strong>Total Amount:</strong> <span class="total-amount">LKR <?= $reservation_pre_order_total_amount; ?></span></p>
                </section>
            <?php endif; ?>

            <section class="success-info">
                <p>If you need to change or cancel your reservation, please contact us. Please arrive at least 10 minutes before your reservation time.</p>
            </section>

            <section class="success-actions">
                <a href="<?php echo BASE_URL; ?>/index.php" class="btn-primary">Go Home</a>
                <a href="<?php echo BASE_URL; ?>/pages/reservations/reservations.php" class="btn-primary">Make another reservation</a>
            </section>
        </section>
    </main>



    <?php
    include('../../components/footer.php');
    echo '<script src="' . BASE_URL . '/public/scripts/reservations.js"></script>';
    echo "<script src='" . BASE_URL . "/public/scripts/profile.js'></script>";
    ?>
</body>

</html>
