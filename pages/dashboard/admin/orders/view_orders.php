<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('ADMIN'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['reason'] == 'change_order_status' && isset($_POST['order_id']) && isset($_POST['order_status'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['order_status'];
        $q = "UPDATE `order` SET `status` = '{$status}', `updated_at` = NOW() WHERE `id` = {$order_id};";

        if (mysqli_query($conn, $q)) {
            echo "<script>alert('Order status updated successfully!');</script>";
        } else echo "<script>alert('Failed to change order status: " . mysqli_error($conn) . "');</script>";
    }

    if ($_POST['reason'] == 'delete_order' && isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
        $q = "UPDATE `order` SET `deleted_at` = NOW() WHERE `id` = {$order_id};";

        if (mysqli_query($conn, $q)) {
            echo "<script>alert('Order deleted successfully!');</script>";
        } else echo "<script>alert('Failed to delete order: " . mysqli_error($conn) . "');</script>";
    }
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
    o.deleted_at IS NULL
ORDER BY
     o.status ASC, o.created_at DESC;
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
    <h1>Admin Dashboard</h1>
    <?php include('../../../../components/header_navigation_bar.php'); ?>

    <main>
        <div class="dashboard-container">

            <?php include('../../../../components/admin_dashboard_side_nav.php'); ?>

            <div class="dashboard-content-container">
                <div class="dashboard-content admin-dashboard-orders">
                    <header>
                        <h2>Orders</h2><a href="<?php echo BASE_URL; ?>/pages/cart/cart.php" class="btn-secondary"><span class="material-symbols-rounded btn-icon">add</span><span>Add Order in Cart</span></a>
                    </header>

                    <table class="orders-container">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Placed On</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Total Price</th>
                                <th class="order-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if (mysqli_num_rows($result) > 0) {
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

                                    $status_buttons = '';
                                    if ($status == 'PENDING') {
                                        $status_buttons  = <<< HTML
                                    <button class="btn-secondary btn-only-icon" title="Mark order as Completed" onclick="updateOrderStatus($order_id, true)">
                                        <span class="material-symbols-rounded btn-icon">check</span>
                                    </button>
                                    <button class="btn-secondary btn-only-icon" title="Mark order as Cancelled" onclick="updateOrderStatus($order_id, false)">
                                        <span class="material-symbols-rounded btn-icon">close</span>
                                    </button>
                                    HTML;
                                    }

                                    echo <<< HTML
                                <tr class="order">
                                    <td class="order-id">#{$order_id}</td>
                                    <td class="order-date">{$created_at}</td>
                                    <td class="order-items">$items</td>
                                    <td class="order-status">{$status}</td>
                                    <td class="order-price">LKR {$total_amount}</td>
                                    <td class="order-actions">
                                        <div class="actions-container">
                                            $status_buttons
                                            <a href="{$BASE_URL}/pages/dashboard/admin/orders/edit_order.php?order_id={$order_id}" class="btn-secondary btn-only-icon" title="Edit Order">
                                                <span class="material-symbols-rounded btn-icon">edit</span>
                                            </a>
                                            <button class="btn-secondary btn-only-icon" title="Delete order" onclick="deleteOrder($order_id)">
                                                <span class="material-symbols-rounded btn-icon">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                HTML;
                                }
                            } else {
                                echo '<tr><td colspan="6" class="no-results">No orders found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php
    include('../../../../components/footer.php');
    mysqli_close($conn);
    echo "<script src='" . BASE_URL . "/public/scripts/view_orders.js'></script>";
    ?>
</body>

</html>
