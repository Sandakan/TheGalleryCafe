<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('ADMIN'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['reason'] == 'delete_menu_item' && isset($_POST['menu_item_id'])) {
        $q = <<<SQL
            UPDATE `menu_item` SET `deleted_at` = NOW() WHERE `id` = {$_POST['menu_item_id']};
        SQL;
        $res = mysqli_query($conn, $q);

        if (!$res) {
            echo mysqli_error($conn);
        }
    }
}

if (!isset($_GET['menu_id'])) {
    echo "Menu ID not found";
    exit();
}
$menu_id = $_GET['menu_id'];

$q5 = <<< SQL
    SELECT 
        id, `name` 
    FROM 
        menu 
    WHERE 
        id = '$menu_id';
SQL;

$res2 = mysqli_query($conn, $q5);
$menu = mysqli_fetch_assoc($res2);


$query = <<<SQL
SELECT
	`menu`.id AS menu_id,
	`menu_item`.id AS menu_item_id,
	`menu`.NAME AS menu_name,
	`menu_item`.`name` AS menu_item_name,
	`menu_item`.price AS menu_item_price,
	`menu_item`.`description` AS menu_item_description,
	`menu_item`.category AS menu_item_category,
	`menu_item`.image AS menu_item_image,
	`menu_item`.`type` AS menu_item_type,
	`cuisine`.`name` AS cuisine_name 
FROM
	`menu_item`
	INNER JOIN `menu` ON `menu`.id = `menu_item`.`menu_id`
	INNER JOIN `cuisine` ON `cuisine`.`id` = `menu_item`.`cuisine_id` 
WHERE
    `menu_item`.`menu_id` = {$_GET['menu_id']}
	AND `menu_item`.`deleted_at` IS NULL 
	AND `menu`.`deleted_at` IS NULL;
SQL;

$result = mysqli_query($conn, $query);
$menu_items  = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu Items of <?php echo $menu['name'] ?> - The Gallery Café</title>
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
                        <h2>Menu Items of <?php echo $menu['name'] ?></h2>
                        <div class="header-actions-container">
                            <a href="<?php echo BASE_URL; ?>/pages/dashboard/admin/menus/add_menu_item.php?menu_id=<?php echo $menu['id'] ?>" class="btn-secondary"><span class="material-symbols-rounded btn-icon">add</span><span>Add Menu Item</span></a>
                        </div>
                    </header>

                    <table class="menus-container">
                        <thead>
                            <tr>
                                <th class="menu-item-id">Menu Item ID</th>
                                <th class="menu-item-name">Name</th>
                                <th class="menu-item-description">Description</th>
                                <th class="menu-item-category">Category</th>
                                <th class="menu-item-type">Type</th>
                                <th class="menu-item-cuisine-type">Cuisine Type</th>
                                <th class="menu-item-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($menu_items) > 0) {
                                foreach ($menu_items as $menu_item) {
                                    $BASE_URL = BASE_URL;
                                    $menu_item_image_url = $BASE_URL . '/public/images/menu-items/' . $menu_item['menu_item_image'];

                                    echo <<<HTML
                                    <tr>
                                        <td class="menu-id">#{$menu_item['menu_item_id']}</td>
                                        <td class="menu-name">
                                            <div>
                                                <img src="$menu_item_image_url" alt="{$menu_item['menu_item_name']}">
                                                <span>{$menu_item['menu_item_name']}</span>
                                            </div>
                                        </td>
                                        <td class="menu-description">{$menu_item['menu_item_description']}</td>
                                        <td class="menu-item-category">{$menu_item['menu_item_category']}</td>
                                        <td class="menu-item-type">{$menu_item['menu_item_type']}</td>
                                        <td class="menu-item-cuisine-name">{$menu_item['cuisine_name']}</td>
                                        <td class="menu-actions">
                                           <div class="actions-container">
                                                <a href="{$BASE_URL}/pages/dashboard/admin/menus/edit_menu_item.php?menu_item_id={$menu_item['menu_item_id']}" class="btn-secondary btn-only-icon" title="Edit Menu Item">
                                                   <span class="material-symbols-rounded btn-icon">edit</span>
                                                </a>
                                                <button class="btn-secondary btn-only-icon" title="Delete Menu Item" onclick="deleteMenuItem({$menu_item['menu_item_id']})">
                                                    <span class="material-symbols-rounded btn-icon">delete</span>
                                                </button>
                                           </div>
                                        </td>
                                    </tr>
                                HTML;
                                }
                            } else {
                                echo '<tr><td colspan="7">No menu items found.</td></tr>';
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
    echo "<script src='" . BASE_URL . "/public/scripts/view_menu_items.js'></script>";
    ?>
</body>

</html>
