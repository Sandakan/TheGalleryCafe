<?php
require '../../config.php';
require '../../utils/database.php';
require '../../components/menu_item.php';

$conn = initialize_database();
session_start();
$BASE_URL = BASE_URL;

$sql = <<< SQL
SELECT
    u.id AS user_id,
    u.created_at,
    COUNT( DISTINCT r.id ) AS number_of_reservations,
    COUNT( DISTINCT o.id ) AS number_of_orders,
    (SELECT COUNT(DISTINCT r.id) 
     FROM reservation r 
     INNER JOIN `order` o ON r.id = o.reservation_id WHERE r.user_id = {$_SESSION['user_id']} AND r.deleted_at IS NULL) AS reservations_with_pre_orders 
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
$data = mysqli_fetch_assoc($res);

$username = $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name'];
$member_since = date('jS \of F Y', strtotime($data['created_at']));
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
                    <div class="profile-model">
                        <div class="profile-header">
                            <h2 class="profile-name"><?= $username ?></h2>
                            <div class="profile-actions-container">
                                <a href="<?= BASE_URL ?>/pages/profile/edit_user_information.php" class="btn-secondary"><span class="material-symbols-rounded">edit</span> <span>Edit</span></a>
                                <button class="btn-secondary" onclick="logout('<?= BASE_URL ?>')"><span class="material-symbols-rounded">logout</span> <span>Log Out</span></button>
                            </div>
                        </div>
                        <div class="profile-info">
                            <p><span class="material-symbols-rounded-filled">celebration</span> <?= ucwords(strtolower($_SESSION['role'])) ?> since <?= $member_since ?>. </p>
                            <!-- <p><span class="material-symbols-rounded-filled">event_seat</span> <?= $data['number_of_reservations'] ?> reservations</p>
                            <p><span class="material-symbols-rounded-filled">orders</span> <?= $data['number_of_orders'] ?> orders</p> -->
                        </div>
                    </div>

                    <div class="stats-container">
                        <div class="stat">
                            <div class="stat-info">
                                <h4 class="stat-title">Total Orders</h4>
                                <div class="stat-value"><?php echo $data['number_of_orders']; ?></div>
                            </div>
                            <span class="stat-icon material-symbols-rounded">orders</span>
                        </div>

                        <div class="stat">
                            <div class="stat-info">
                                <h4 class="stat-title">Total Reservations</h4>
                                <div class="stat-value"><?php echo $data['number_of_reservations']; ?></div>
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

    <?php
    include('../../components/footer.php');
    echo "<script src='" . BASE_URL . "/public/scripts/profile.js'></script>";
    mysqli_close($conn);
    ?>
</body>

</html>
