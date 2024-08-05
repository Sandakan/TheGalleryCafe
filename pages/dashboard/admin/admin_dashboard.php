<?php
require '../../../config.php';
require '../../../utils/database.php';

$conn = initialize_database();
session_start();

if (isset($_SESSION["role"]) != 'ADMIN') {
    header("Location: " . BASE_URL . "/index.php");
}

$query = <<<SQL
SELECT
    (SELECT COUNT(*) FROM `user`) AS total_users,
    (SELECT COUNT(*) FROM `order`) AS total_orders,
    (SELECT COUNT(*) FROM reservation) AS total_reservations,
    (SELECT COUNT(DISTINCT r.id) 
     FROM reservation r 
     INNER JOIN `order` o ON r.id = o.reservation_id) AS reservations_with_pre_orders;
SQL;

$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/dashboard.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../../components/header_navigation_bar.php'); ?>

    <main>
        <div class="dashboard-container">

            <?php include('../../../components/admin_dashboard_side_nav.php'); ?>

            <div class="dashboard-content-container">
                <div class="dashboard-content admin-dashboard-stats">
                    <header>
                        <h2>Statistics</h2>
                    </header>
                    <div class="stats-container">
                        <div class="stat">
                            <div class="stat-info">
                                <h4 class="stat-title">Total Orders</h4>
                                <div class="stat-value"><?php echo $data['total_orders']; ?></div>
                            </div>
                            <span class="stat-icon material-symbols-rounded">orders</span>
                        </div>

                        <div class="stat">
                            <div class="stat-info">
                                <h4 class="stat-title">Total Users</h4>
                                <div class="stat-value"><?php echo $data['total_users']; ?></div>
                            </div>
                            <span class="stat-icon material-symbols-rounded">group</span>
                        </div>

                        <div class="stat">
                            <div class="stat-info">
                                <h4 class="stat-title">Total Reservations</h4>
                                <div class="stat-value"><?php echo $data['total_reservations']; ?></div>
                            </div>
                            <span class="stat-icon material-symbols-rounded">event_seat</span>
                        </div>

                        <div class="stat">
                            <div class="stat-info">
                                <h4 class="stat-title">Pre-order Reservations</h4>
                                <div class="stat-value"><?php echo $data['reservations_with_pre_orders']; ?></div>
                            </div>
                            <span class="stat-icon material-symbols-rounded">restaurant_menu</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('../../../components/footer.php'); ?>
</body>

</html>
