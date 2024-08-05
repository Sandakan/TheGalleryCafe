<?php
require '../../../../config.php';
require '../../../../utils/database.php';

$conn = initialize_database();
session_start();

if (isset($_SESSION["role"]) != 'ADMIN') {
    header("Location: " . BASE_URL . "/index.php");
}

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
    o.user_id = {$_SESSION['user_id']}
ORDER BY
    o.created_at DESC;
SQL;

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orders - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/dashboard.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../../../components/header_navigation_bar.php'); ?>

    <main>
        <div class="dashboard-container">

            <?php include('../../../../components/admin_dashboard_side_nav.php'); ?>

            <div class="dashboard-content-container">
                <div class="dashboard-content admin-dashboard-orders">
                    <h2>Orders</h2>

                    <table class="orders-container">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Placed On</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                                $order_id = $row['id'];
                                $created_at = date('Y F jS h:i:s A', strtotime($row['created_at']));
                                $total_amount = $row['total_amount'];
                                $status = $row['status'];
                                $reservation_id = $row['reservation_id'];


                                $q2 = <<< SQL
                            SELECT
                                mi.name,
                                oi.quantity
                            FROM
                                order_item oi
                                LEFT JOIN menu_item mi ON mi.id = oi.menu_item_id
                            WHERE
                                oi.order_id = {$order_id};
                            SQL;
                                $res2 = mysqli_query($conn, $q2);
                                $items = '';
                                if (mysqli_num_rows($res2) > 0) {
                                    while ($row = mysqli_fetch_assoc($res2)) {
                                        $items .= $row['name'] . ' x' . $row['quantity'] . '<br>';
                                    }
                                } else $items = 'N/A';

                                echo <<< HTML
                            <tr class="order">
                                    <td class="order-id">#{$order_id}</td>
                                    <td class="order-date">{$created_at}</td>
                                    <td class="order-items">$items</td>
                                    <td class="order-status">{$status}</td>
                                    <td class="order-price">LKR {$total_amount}</td>
                            </tr>
                        HTML;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php include('../../../../components/footer.php'); ?>
</body>

</html>
