<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('ADMIN'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['reason'] == 'delete_promotion' && isset($_POST['promotion_id'])) {
        $q = <<<SQL
            UPDATE `promotion` SET `deleted_at` = NOW() WHERE `id` = {$_POST['promotion_id']};
        SQL;
        $res = mysqli_query($conn, $q);

        if (!$res) {
            echo mysqli_error($conn);
        }
    }
}

$query = <<<SQL
    SELECT * FROM `promotion` WHERE `deleted_at` IS NULL;
SQL;

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Promotions - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/dashboard.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../../../components/header_navigation_bar.php'); ?>

    <main>
        <h1>Admin Dashboard</h1>
        <div class="dashboard-container">

            <?php include('../../../../components/admin_dashboard_side_nav.php'); ?>

            <div class="dashboard-content-container">
                <div class="dashboard-content admin-dashboard-promotions">
                    <header>
                        <h2>Promotions</h2>
                        <div class="header-actions-container">
                            <a href="<?php echo BASE_URL; ?>/pages/dashboard/admin/promotions/add_promotion.php" class="btn-secondary"><span class="material-symbols-rounded btn-icon">add</span><span>Add Promotion</span></a>
                        </div>
                    </header>

                    <table class="promotions-container">
                        <thead>
                            <tr>
                                <th class="promotion-id">Promotion ID</th>
                                <th class="promotion-name">Name</th>
                                <th class="promotion-description">Description</th>
                                <th class="promotion-discount-percentage">Discount Percentage</th>
                                <th class="promotion-starts-at">Starts At</th>
                                <th class="promotion-ends-at">Ends At</th>
                                <th class="promotion-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $percentage = floatval($row['discount_percentage']) * 100;

                                    echo <<<HTML
                                    <tr>
                                        <td class="promotion-id">#{$row['id']}</td>
                                        <td class="promotion-name">{$row['name']}</td>
                                        <td class="promotion-description">{$row['description']}</td>
                                        <td class="promotion-discount-percentage">{$percentage}%</td>
                                        <td class="promotion-starts-at">{$row['starts_at']}</td>
                                        <td class="promotion-ends-at">{$row['ends_at']}</td>
                                        <td class="promotion-actions">
                                           <div class="actions-container">
                                               <a href="{$BASE_URL}/pages/dashboard/admin/promotions/edit_promotion.php?promotion_id={$row['id']}" class="btn-secondary btn-only-icon" title="Edit Promotion">
                                                   <span class="material-symbols-rounded btn-icon">edit</span>
                                                </a>
                                                <button class="btn-secondary btn-only-icon" title="Delete Promotion" onclick="deletePromotion({$row['id']})">
                                                    <span class="material-symbols-rounded btn-icon">delete</span>
                                                </button>
                                           </div>
                                        </td>
                                    </tr>
                                HTML;
                                }
                            } else {
                                echo '<tr><td colspan="7" class="no-results">No promotions found.</td></tr>';
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
    echo "<script src='" . BASE_URL . "/public/scripts/view_promotions.js'></script>";
    ?>
</body>

</html>
