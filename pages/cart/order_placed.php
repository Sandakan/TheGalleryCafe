<?php
require '../../config.php';
require '../../utils/database.php';
require '../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate();
$user_id = $_SESSION["user_id"];

if (!isset($_GET["order_id"])) {
    echo "Order ID not found";
    exit();
}

$order_id = $_GET["order_id"];

// get order info

$query = <<< SQL
SELECT
    o.id,
    o.created_at,
    o.total_amount,
    o.status,
    o.reservation_id
FROM
    `order` o
WHERE 
    o.id = {$order_id}
ORDER BY
     o.status ASC, o.created_at DESC;
SQL;

$res = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($res);

if (!$order) {
    echo "Order not found";
    exit();
}

$order_id = $order["id"];
$order_created_at = date('F j, Y \a\t g:i A', strtotime($order["created_at"]));
$order_total_amount = $order["total_amount"];
$order_status = $order["status"];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Placed Successfully - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/order_reservation_success.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <main class="">

        <section class="order-success">
            <header>
                <span class="material-symbols-rounded-filled">verified</span>
                <h1>Order #<?php echo $order_id; ?> Placed Successfully.</h1>
            </header>

            <section class="success-details">
                <p>Your order has been successfully made. Here are your order details:</p>
                <ul>
                    <li><strong>Order Number:</strong> #<?= $order["id"]; ?></li>
                    <li><strong>Order Created At:</strong> <?= $order_created_at; ?></li>
                </ul>
            </section>

            <section class="success-details">
                <p>Your order items:</p>
                <ul>
                    <?php
                    $q1 = <<< SQL
                    SELECT
                        o.id,
                        o.created_at,
                        o.total_amount,
                        o.status,
                        o.reservation_id
                    FROM
                        `order` o
                    WHERE 
                        o.id = {$order_id}
                    ORDER BY
                        o.status ASC, o.created_at DESC;
                    SQL;

                    $res1 = mysqli_query($conn, $q1);
                    $order = mysqli_fetch_assoc($res1);

                    if (!$order) {
                        echo "Order not found";
                        exit();
                    }

                    $order_id = $order["id"];
                    $order_total_amount = $order["total_amount"];
                    $order_status = $order["status"];

                    $q2 = <<< SQL
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
                        oi.order_id = {$order_id};
                    SQL;

                    $res2 = mysqli_query($conn, $q2);

                    while ($row = mysqli_fetch_assoc($res2)) {
                        $quantity = $row["quantity"];

                        echo <<< HTML
                        <li>{$quantity} x {$row["name"]} - LKR {$row["price"]}</li>
                        HTML;
                    }
                    ?>
                </ul>

                <p class="order-total-amount"><strong>Total Amount:</strong> <span class="total-amount">LKR <?php echo $order_total_amount; ?></span></p>
            </section>


            <section class="success-info">
                <p>If you need to make any changes to your order, please contact us.</p>
                <p>Thank you for choosing us.</p>
            </section>

            <section class="success-actions">
                <a href="<?php echo BASE_URL; ?>/index.php" class="btn-primary">Go Home</a>
                <a href="<?php echo BASE_URL; ?>/pages/menu/menu.php" class="btn-primary">Make another order</a>
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
