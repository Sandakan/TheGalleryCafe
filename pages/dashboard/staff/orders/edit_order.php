<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('STAFF'));
$current_order_status = $current_order_reservation_id = '';
$order_status = '';
$order_status_error = '';

$is_error = false;
$BASE_URL = BASE_URL;
$order_statuses = array('PENDING', 'COMPLETED', 'CANCELLED');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reason'])) {
    if ($_POST['reason'] == 'change_item_quantity') {
        if (isset($_POST['order_item_id']) && isset($_POST['current_quantity']) && isset($_POST['incrementing_value'])) {
            $order_item_id = $_POST['order_item_id'];
            $current_quantity = $_POST['current_quantity'];
            $incrementing_value = $_POST['incrementing_value'];
            $new_quanitty = $current_quantity + $incrementing_value;

            if ($new_quanitty > 0) {
                $q = <<<SQL
                UPDATE `order_item` SET `quantity` = $new_quanitty WHERE `id` = $order_item_id;
                SQL;

                if (mysqli_query($conn, $q)) {
                    echo "success";
                } else echo "<script>alert('Failed to change item quantity: " . mysqli_error($conn) . "');</script>";
            } else {
                // remove item from order
                $q  = "UPDATE order_item SET deleted_at = NOW() WHERE id = $order_item_id;";

                if (mysqli_query($conn, $q)) {
                    echo "success";
                } else echo "<script>alert('Failed to remove item from cart: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
    if ($_POST['reason'] == 'add_item_to_order' && isset($_POST['menu_item_id']) && isset($_POST['order_id'])) {
        $menu_item_id = $_POST['menu_item_id'];
        $adding_order_id = $_POST['order_id'];
        $q = "INSERT INTO `order_item` (`menu_item_id`, `order_id`, `quantity`) VALUES ($menu_item_id, $adding_order_id, 1);";
        if (mysqli_query($conn, $q)) {
            echo "success";
        }
    }
}

if (!isset($_GET['order_id'])) {
    echo "Order ID not found";
    exit();
}
$order_id = $_GET['order_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['order_status']) && array_search($_POST['order_status'], $order_statuses) !== false) {
        $order_status = $_POST['order_status'];

        $q = <<<SQL
        UPDATE `order` SET `status` = '$order_status' WHERE `id` = $order_id;
        SQL;

        if (mysqli_query($conn, $q)) {
            echo "success";
            header("Location: " . BASE_URL . "/pages/dashboard/staff/orders/view_orders.php");
        }
    }
}

$q = <<<SQL
    SELECT * FROM `order` WHERE `id` = {$order_id} LIMIT 1;
SQL;

$result = mysqli_query($conn, $q);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
} else {
    $order = mysqli_fetch_assoc($result);

    if (!$order) {
        echo "Order not found";
        exit();
    } else {
        $current_order_status = $order_status = $order['status'];
        $current_order_reservation_id = $order['reservation_id'];
    }
}

function renderOrderItem($row)
{

    $order_item_id = $row['order_item_id'];
    $menu_item_name = $row['name'];
    $menu_item_quantity = $row['quantity'];
    $menu_item_price = $row['price'];
    $menu_item_total_price = $row['total_price'];
    $menu_item_image = BASE_URL . '/public/images/menu-items/' . $row['image'];

    $removeButton = $menu_item_quantity == 1 ? "<span class='material-symbols-rounded delete-icon'>delete</span>" : "<span class='material-symbols-rounded'>remove</span>";
    echo <<< HTML
     <tr>
        <td><div class="menu-item"><img src="$menu_item_image" /><span>$menu_item_name</span></div></td>
        <td>
            <div class="cart-items-incrementing-actions-container">
                <button type="button" class="btn-secondary" onclick="changeItemQuantity($order_item_id,$menu_item_quantity,-1)">
                    $removeButton
                </button>
                <span class="selected-item-count">$menu_item_quantity</span>
                <button type="button" class="btn-secondary" onclick="changeItemQuantity($order_item_id,$menu_item_quantity,1)">
                    <span class="material-symbols-rounded">add</span>
                </button>    
            </div>
        </td>
        <td>LKR $menu_item_price</td>
        <td>LKR $menu_item_total_price</td>
    </tr>
    HTML;
}


$sql = <<< SQL
SELECT
    order_item.id AS order_item_id,
	menu_item.`name`,
	menu_item.price,
	menu_item.image,
	order_item.quantity,
	( order_item.quantity * menu_item.price ) AS total_price,
	(
	SELECT
		SUM( order_item.quantity * menu_item.price ) 
	FROM
		order_item
		LEFT JOIN menu_item  ON order_item.menu_item_id = menu_item.id 
	WHERE
		order_item.order_id = $order_id 
		AND order_item.deleted_at IS NULL 
	) AS total_order_price 
FROM
	`order_item`
	INNER JOIN `menu_item` ON `menu_item`.id = order_item.menu_item_id 
WHERE
	order_item.order_id = $order_id AND order_item.deleted_at IS NULL;
SQL;

$result = mysqli_query($conn, $sql);
$order_items_count = mysqli_num_rows($result);
$total_order_price = 0.00;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta menu_name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editing Order #<?= $order_id ?> - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/cart.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../../../components/header_navigation_bar.php'); ?>

    <main>
        <div class="dashboard-container">

            <?php include('../../../../components/staff_dashboard_side_nav.php'); ?>

            <div class="dashboard-content-container">
                <div class="dashboard-content staff-dashboard-menus">
                    <header>
                        <h2>Editing Order #<?= $order_id ?></h2>
                    </header>


                    <form class="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>">

                        <div class="input-container">
                            <label for="cart_table">Order Items</label>
                            <table class="cart-table" id="cart-table">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price per item</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if ($order_items_count > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $order_item_id = $row['order_item_id'];
                                            $total_order_price += $row['total_price'];
                                            renderOrderItem($row);
                                        }
                                    } else {
                                        echo <<< HTML
                                <tr class="no-items-in-cart">
                                    <td colspan="4">
                                        <strong>No items in the order.</strong>
                                    </td>
                                </tr>
                                HTML;
                                    }

                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="total" colspan="3">Total</td>
                                        <td>LKR <?= round($total_order_price, 2); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <hr>


                        <div class="items-search-container">
                            <header>
                                <h5>Add New Items</h5>
                                <div class="search-input-container">
                                    <input type="search" id="items-search" name="q" placeholder="Search menu items here" />
                                    <button class="btn-secondary" id="search-items-btn" type="button" onclick="searchItems('<?= $BASE_URL ?>', <?= $order_id ?>)">Search</button>
                                </div>
                            </header>

                            <div class="item-search-results" id="search-results">
                                <div class="no-results">Search to display results.</div>
                            </div>
                        </div>

                        <hr>

                        <div class="input-container">
                            <label for="order_status">Order Status *</label>
                            <select name="order_status" id="order_status" required>
                                <?php
                                foreach ($order_statuses as $status) {
                                    $selected =  $status == $current_order_status  ? 'selected' : '';
                                    echo '<option value="' . $status . '" ' . $selected . '>' . $status . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <hr />


                        <button class="btn-primary form-submit-btn" type="submit">Update Order</button>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php
    include('../../../../components/footer.php');
    echo '<script src="' . BASE_URL . '/public/scripts/edit_order.js"></script>';
    ?>
</body>

</html>
