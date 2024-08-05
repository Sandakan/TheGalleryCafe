<?php
require '../../../../config.php';
require '../../../../utils/database.php';

$conn = initialize_database();
session_start();

if (isset($_SESSION["role"]) != 'ADMIN') {
    header("Location: " . BASE_URL . "/index.php");
    exit();
}

$query =
    <<<SQL
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
ORDER BY
    DATEDIFF(tr.starts_at, NOW()) DESC;
SQL;

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reservations - The Gallery Café</title>
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
                <div class="dashboard-content admin-dashboard-reservations">
                    <h2>Reservations</h2>

                    <table class="reservations-container">
                        <thead>
                            <tr>
                                <th>Reservation ID</th>
                                <th>Pre-ordered Items</th>
                                <th>Starts At</th>
                                <th>Table ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
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
