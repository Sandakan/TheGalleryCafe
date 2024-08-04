<?php
require '../../config.php';
require '../../utils/database.php';
require '../../components/menu_item.php';

$conn = initialize_database();
session_start();
$BASE_URL = BASE_URL;
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
    <link rel="shortcut icon" href="<?= BASE_URL ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <main>


        <div class="model-container profile-model-container">
            <div class="model profile-model">
                <?php
                $sql = <<< SQL
                SELECT
                    u.id AS user_id,
                    u.created_at,
                    COUNT( DISTINCT r.id ) AS number_of_reservations,
                    COUNT( DISTINCT o.id ) AS number_of_orders 
                FROM
                    USER u
                    LEFT JOIN reservation r ON u.id = r.user_id
                    LEFT JOIN `order` o ON u.id = o.user_id 
                WHERE
                    u.id = {$_SESSION['user_id']} 
                GROUP BY
                    u.id,
                    u.created_at;
            SQL;

                $res = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($res);

                $username = $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name'];
                $member_since = date('jS \of F Y', strtotime($row['created_at']));

                echo <<< HTML
                <div class="profile-header">
                    <h2 class="profile-name">$username</h2>
                    <div class="profile-actions-container">
                        <button class="btn-secondary"><span class="material-symbols-rounded">edit</span> <span>Edit</span></button>
                        <button class="btn-secondary" onclick="logout('$BASE_URL')"><span class="material-symbols-rounded">logout</span> <span>Log Out</span></button>  
                    </div>
                </div>
                <div class="profile-info">
                    <p><span class="material-symbols-rounded-filled">celebration</span> Member since $member_since. </p>
                    <p><span class="material-symbols-rounded-filled">event_seat</span> {$row['number_of_reservations']} reservations</p>
                    <p><span class="material-symbols-rounded-filled">orders</span> {$row['number_of_orders']} orders</p>
                </div>
            HTML;
                ?>

            </div>
        </div>

        <section class="orders-container">
            <h2>Your Orders</h2>
            <table class="orders">
                <thead>
                    <tr>
                        <th class="order-id">Order ID</th>
                        <th class="order-date">Placed On</th>
                        <th class="order-items">Items</th>
                        <th class="order-status">Status</th>
                        <th class="order-price">Total Price</th>
                    </tr>
                </thead>
                <tbody>
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
                        o.user_id = {$_SESSION['user_id']}
                    ORDER BY
                        o.created_at DESC;
                    SQL;
                    $res1 = mysqli_query($conn, $q1);

                    if (mysqli_num_rows($res1) > 0) {
                        while ($row = mysqli_fetch_assoc($res1)) {
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
                    } else {
                        echo <<< HTML
                        <tr class="order">
                                <td colspan="4" class="order-id">No Orders found</td>
                        </tr>
                    HTML;
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="reservations-container">
            <h2>Your Reservations</h2>
            <table class="reservations">
                <thead>
                    <tr>
                        <th class="reservation-id">Reservation ID</th>
                        <th class="reservation-price">Pre-ordered Items</th>
                        <th class="reservation-date">Starts At</th>
                        <th class="reservation-status">Table ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q3 = <<< SQL
                    SELECT
                        r.id,
                        o.id AS order_id,
                        tr.starts_at,
                        tr.ends_at,
                        tr.table_id
                    FROM
                        `reservation` r
                    LEFT JOIN `table_reservation` tr ON r.table_reservation_id = tr.id
                    LEFT JOIN `order` o ON o.reservation_id = r.id
                    WHERE
                        r.user_id = {$_SESSION['user_id']}
                    ORDER BY
                        DATEDIFF(tr.starts_at, NOW()) DESC;
                    SQL;
                    $res3 = mysqli_query($conn, $q3);

                    if (mysqli_num_rows($res3) > 0) {
                        while ($row = mysqli_fetch_assoc($res3)) {
                            $reservation_id = $row['id'];
                            $starts_at = date('Y F jS h:i:s A', strtotime($row['starts_at']));
                            $table_id = $row['table_id'];
                            $reservation_order_id = isset($row['order_id']) ? '#' . $row['order_id']  : 'N/A';

                            $reservation_preordered_items = '';
                            if (isset($row['order_id'])) {
                                $q4 = <<< SQL
                            SELECT
                                mi.name,
                                oi.quantity
                            FROM
                                order_item oi
                                LEFT JOIN menu_item mi ON mi.id = oi.menu_item_id
                            WHERE
                                oi.order_id = {$row['order_id']};
                            SQL;
                                $res4 = mysqli_query($conn, $q4);
                                if (mysqli_num_rows($res4) > 0) {
                                    while ($row = mysqli_fetch_assoc($res4)) {
                                        $reservation_preordered_items .= $row['name'] . ' x' . $row['quantity'] . '<br>';
                                    }
                                }
                            } else $reservation_preordered_items = 'N/A';

                            echo <<< HTML
                            <tr class="reservation">
                                    <td class="reservation-id">#{$reservation_id}</td>
                                    <td class="reservation-pre-order-items">{$reservation_preordered_items}</td>
                                    <td class="reservation-date">{$starts_at}</td>
                                    <td class="reservation-table_id">#{$table_id}</td>
                            </tr>
                            HTML;
                        }
                    } else {
                        echo <<< HTML
                        <tr class="order">
                                <td colspan="4">No Reservations found</td>
                        </tr>
                    HTML;
                    }
                    ?>
                </tbody>
            </table>
        </section>

    </main>

    <?php
    include('../../components/footer.php');
    echo "<script src='" . BASE_URL . "/public/scripts/profile.js'></script>";
    mysqli_close($conn);
    ?>
</body>

</html>
