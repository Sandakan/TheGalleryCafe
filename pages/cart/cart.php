<?php
require '../../config.php';
require '../../utils/database.php';
require '../../components/menu_item.php';

$conn = initialize_database();
session_start();

if (!isset($_SESSION["user_id"])) {
    $currentUrl = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    header("Location: " . BASE_URL . "/pages/auth/login.php?redirect=" . urlencode($currentUrl));
    exit();
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['reason'] == 'change_item_quantity') {
        $item_id = $_POST['cart_item_id'];
        $current_quantity = $_POST['current_quantity'];
        $incrementing_value = $_POST['incrementing_value'];
        $new_quanitty = $current_quantity + $incrementing_value;

        if ($new_quanitty > 0) {
            // update quantity
            $sql  = "UPDATE cart_item SET quantity = $new_quanitty WHERE id = $item_id;";

            if (mysqli_query($conn, $sql)) {
                echo "success";
            } else echo "<script>alert('Failed to change item quantity: " . mysqli_error($conn) . "');</script>";
        } else {
            // remove item from cart
            $sql  = "UPDATE cart_item SET deleted_at = NOW() WHERE id = $item_id;";
            if (mysqli_query($conn, $sql)) {
                echo "success";
            } else echo "<script>alert('Failed to remove item from cart: " . mysqli_error($conn) . "');</script>";
        }
    }

    if ($_POST['reason'] == 'confirm_order') {
        $totalAmount = 0.00;

        // create a new order
        $q1 = "INSERT INTO `order` (user_id, total_amount) VALUES ($user_id, $totalAmount)";
        if (mysqli_query($conn, $q1)) {
            $orderId = mysqli_insert_id($conn);

            $cartId = $_POST['cart_id'];
            // fetch current cart items
            $q2 = "SELECT * FROM cart_item INNER JOIN menu_item ON cart_item.menu_item_id = menu_item.id WHERE cart_id = $cartId AND cart_item.deleted_at IS NULL";
            $result = mysqli_query($conn, $q2);

            while ($row = mysqli_fetch_assoc($result)) {
                $menuItemId = $row['menu_item_id'];
                $quantity = $row['quantity'];
                $price = $row['price'];

                // add each cart item to the order as a new order item
                $q = "INSERT INTO order_item (order_id, menu_item_id, quantity) VALUES ($orderId, $menuItemId, $quantity)";
                mysqli_query($conn, $q);

                $totalAmount += $price * $quantity;
            }

            // update order with the new total amount
            $q3 = "UPDATE `order` SET total_amount = $totalAmount WHERE id = $orderId";
            if (mysqli_query($conn, $q3)) {

                // delete cart
                $q4 = "UPDATE cart SET deleted_at = NOW() WHERE id = $cartId AND deleted_at IS NULL";
                if (mysqli_query($conn, $q4)) {
                    echo "<script>alert('Order created successfully!');</script>";
                    header("Location: " . BASE_URL . "/pages/cart/order_placed.php");
                } else echo "<script>alert('Failed to delete cart: " . mysqli_error($conn) . "');</script>";
            } else echo "<script>alert('Failed to create order: " . mysqli_error($conn) . "');</script>";
        } else echo "<script>alert('Failed to create order: " . mysqli_error($conn) . "');</script>";
    }
}


$sql = <<< SQL
    SELECT
        ci.id AS cart_item_id,
        ci.cart_id,
        ci.menu_item_id,
        ci.quantity,
        mi.NAME AS menu_item_name,
        mi.description AS menu_item_description,
        mi.price,
        mi.category,
        mi.cuisine_id,
        mi.type,
        mi.image,
        ( ci.quantity * mi.price ) AS total_price,
        (
        SELECT
            SUM( ci.quantity * mi.price ) 
        FROM
            cart_item ci
            LEFT JOIN menu_item mi ON ci.menu_item_id = mi.id 
        WHERE
            ci.cart_id = c.id 
            AND ci.deleted_at IS NULL 
        ) AS total_cart_price 
    FROM
        cart c
        LEFT JOIN cart_item ci ON c.id = ci.cart_id
        LEFT JOIN menu_item mi ON ci.menu_item_id = mi.id 
    WHERE
        c.user_id = 1 
        AND c.deleted_at IS NULL 
        AND ci.deleted_at IS NULL
    ORDER BY
        ci.created_at ASC;
SQL;

$result = mysqli_query($conn, $sql);
$cart_items_count = mysqli_num_rows($result);
$total_cart_price = 0.00;
$cart_id;

function renderCartItem($row)
{

    $cart_item_id = $row['cart_item_id'];
    $menu_item_name = $row['menu_item_name'];
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
                <button type="button" class="btn-secondary" onclick="changeItemQuantity($cart_item_id,$menu_item_quantity,-1)">
                    $removeButton
                </button>
                <span class="selected-item-count">$menu_item_quantity</span>
                <button type="button" class="btn-secondary" onclick="changeItemQuantity($cart_item_id,$menu_item_quantity,1)">
                    <span class="material-symbols-rounded">add</span>
                </button>    
            </div>
        </td>
        <td>LKR $menu_item_price</td>
        <td>LKR $menu_item_total_price</td>
    </tr>
    HTML;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Cart - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/cart.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <main>
        <section class="cart">
            <div class="cart-info-container">
                <h1 class="create-reservation-heading">Cart</h1>
            </div>

            <table class="cart-table">
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

                    if ($cart_items_count > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $cart_id = $row['cart_id'];
                            $total_cart_price += $row['total_price'];
                            renderCartItem($row);
                        }
                    } else {
                        echo <<< HTML
                            <tr class="no-items-in-cart">
                                <td colspan="4">
                                    <strong>No items in cart.</strong>
                                    <p>Add items to the cart by visiting the Menu.</p>
                                </td>
                            </tr>
                        HTML;
                    }

                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="total" colspan="3">Total</td>
                        <td>LKR <?= round($total_cart_price, 2); ?></td>
                    </tr>
                </tfoot>
            </table>

            <?php if ($cart_items_count > 0) { ?>
                <div class="cart-actions-container">
                    <button type="button" class="btn btn-primary" onclick="confirmOrder(<?= $cart_id ?>)">Confirm Order</button>
                </div>
            <?php } ?>

        </section>
    </main>

    <?php
    echo '<script src="' . BASE_URL . '/public/scripts/cart.js"></script>';

    include('../../components/footer.php');
    mysqli_close($conn);
    ?>
</body>

</html>
