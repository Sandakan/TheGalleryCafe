<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();
authenticate(array('ADMIN'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['reason'] == 'delete_reservation' && isset($_POST['reservation_id'])) {
        $q = <<<SQL
            UPDATE `reservation` SET `deleted_at` = NOW() WHERE `id` = {$_POST['reservation_id']};
        SQL;
        $res = mysqli_query($conn, $q);

        if (!$res) {
            echo mysqli_error($conn);
        }

        $q2 = <<<SQL
            UPDATE `table_reservation` SET `deleted_at` = NOW() WHERE `id` = (SELECT `table_reservation_id` FROM `reservation` WHERE `id` = {$_POST['reservation_id']});
        SQL;

        $res2 = mysqli_query($conn, $q2);
        if (!$res2) {
            echo mysqli_error($conn);
        }

        $q3 = <<<SQL
            UPDATE `order` SET `deleted_at` = NOW() WHERE `reservation_id` = {$_POST['reservation_id']};
        SQL;

        $res3 = mysqli_query($conn, $q3);
        if (!$res3) {
            echo mysqli_error($conn);
        }

        echo "success";
    }
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
WHERE
    r.deleted_at IS NULL AND o.deleted_at IS NULL
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
    <h1>Admin Dashboard</h1>
    <?php include('../../../../components/header_navigation_bar.php'); ?>

    <main>
        <div class="dashboard-container">

            <?php include('../../../../components/admin_dashboard_side_nav.php'); ?>

            <div class="dashboard-content-container">
                <div class="dashboard-content admin-dashboard-reservations">
                    <header>
                        <h2>Reservations</h2>
                        <a href="<?php echo BASE_URL; ?>/pages/reservations/reservations.php" class="btn-secondary"><span class="material-symbols-rounded btn-icon">add</span><span>Add Reservation</span></a>
                    </header>

                    <table class="reservations-container">
                        <thead>
                            <tr>
                                <th>Reservation ID</th>
                                <th>Pre-ordered Items</th>
                                <th>Starts At</th>
                                <th>Table ID</th>
                                <th class="reservation-actions">Actions</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                            $BASE_URL = BASE_URL;

                            if (mysqli_num_rows($result) > 0) {
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
                                <td class="reservation-actions">
                                    <div class="actions-container">
                                        <a href="{$BASE_URL}/pages/dashboard/admin/reservations/edit_reservation.php?reservation_id={$reservation_id}" class="btn-secondary btn-only-icon" title="Edit Reservation">
                                            <span class="material-symbols-rounded btn-icon">edit</span>
                                        </a>
                                         <button class="btn-secondary btn-only-icon" title="Delete Menu" onclick="deleteReservation({$reservation_id})">
                                            <span class="material-symbols-rounded btn-icon">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            HTML;
                                }
                            } else {
                                echo '<tr><td colspan="5" class="no-results">No reservations found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php include('../../../../components/footer.php');
    echo "<script src='" . BASE_URL . "/public/scripts/view_reservations.js'></script>";
    ?>
</body>

</html>
