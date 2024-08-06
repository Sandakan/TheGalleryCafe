<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('ADMIN'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['reason'] == 'delete_menu' && isset($_POST['menu_id'])) {
        $q = <<<SQL
            UPDATE `menu` SET `deleted_at` = NOW() WHERE `id` = {$_POST['menu_id']};
        SQL;
        $res = mysqli_query($conn, $q);

        if (!$res) {
            echo mysqli_error($conn);
        }
    }
}

$query = <<<SQL
    SELECT * FROM `menu` WHERE `deleted_at` IS NULL;
SQL;

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menus - The Gallery Café</title>
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
                <div class="dashboard-content admin-dashboard-menus">
                    <header>
                        <h2>Menus</h2>
                        <div class="header-actions-container">
                            <a href="<?php echo BASE_URL; ?>/pages/dashboard/admin/menus/add_menu.php" class="btn-secondary"><span class="material-symbols-rounded btn-icon">add</span><span>Add Menu</span></a>
                        </div>
                    </header>

                    <table class="menus-container">
                        <thead>
                            <tr>
                                <th class="menu-id">Menu ID</th>
                                <th class="menu-name">Name</th>
                                <th class="menu-description">Description</th>
                                <th class="menu-description">No of Menu Items</th>
                                <th class="menu-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                                $BASE_URL = BASE_URL;

                                $q1 = <<<SQL
                                SELECT COUNT(*) AS menu_item_count FROM `menu_item` WHERE `menu_id` = {$row['id']};
                                SQL;

                                $res1 = mysqli_query($conn, $q1);
                                $row1 = mysqli_fetch_assoc($res1);

                                echo <<<HTML
                                    <tr>
                                        <td class="menu-id">#{$row['id']}</td>
                                        <td class="menu-name">{$row['name']}</td>
                                        <td class="menu-description">{$row['description']}</td>
                                        <td class="menu-item-count">{$row1['menu_item_count']}</td>
                                        <td class="menu-actions">
                                           <div class="actions-container">
                                               <a href="{$BASE_URL}/pages/dashboard/admin/menus/view_menu_items.php?menu_id={$row['id']}" class="btn-secondary btn-only-icon" title="View Menu Items">
                                                   <span class="material-symbols-rounded btn-icon">visibility</span>
                                                </a>
                                               <a href="{$BASE_URL}/pages/dashboard/admin/menus/edit_menu.php?menu_id={$row['id']}" class="btn-secondary btn-only-icon" title="Edit Menu">
                                                   <span class="material-symbols-rounded btn-icon">edit</span>
                                                </a>
                                                <button class="btn-secondary btn-only-icon" title="Delete Menu" onclick="deleteMenu({$row['id']})">
                                                    <span class="material-symbols-rounded btn-icon">delete</span>
                                                </button>
                                           </div>
                                        </td>
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
    include('../../../../components/footer.php');
    mysqli_close($conn);
    echo "<script src='" . BASE_URL . "/public/scripts/view_menus.js'></script>";
    ?>
</body>

</html>
