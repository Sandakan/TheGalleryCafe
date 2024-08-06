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
    <title><?= $_SESSION['user_first_name'] . '\'s Reservations - ' ?>The Gallery Café</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/profile.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/dashboard.css">
    <link rel="shortcut icon" href="<?= BASE_URL ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <main>

        <div class="dashboard-container">
            <?php include('../../components/profile_dashboard_side_nav.php'); ?>
            <div class="dashboard-content-container">
                <div class="dashboard-content staff-dashboard-reservations">
                    <header>
                        <h2>My Reservations</h2>
                    </header>
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
                </div>
            </div>
        </div>

    </main>

    <?php
    include('../../components/footer.php');
    echo "<script src='" . BASE_URL . "/public/scripts/profile.js'></script>";
    mysqli_close($conn);
    ?>
</body>

</html>
