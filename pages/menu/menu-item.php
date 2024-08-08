<?php
require '../../config.php';
require '../../utils/database.php';
require '../../components/menu_item.php';

$conn = initialize_database();
session_start();


function replaceUnderscoreWithSpaceAndConvertToCapitalCase($str)
{
    return ucwords(str_replace('_', ' ', strtolower($str)));
}

if (!isset($_GET['product-id'])) {
    header('Location: ' . BASE_URL . '/pages/menu/menu.php');
    exit();
}
$menu_item_id = $_GET['product-id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["user_id"])) {
        echo '<script>alert("Please login to add items to cart");</script>';
        // header('Location: ' . BASE_URL . '/pages/auth/login.php');
    } else {
        $selected_item_count = $_POST['selected_item_count'];
        // echo '<script>alert("' . $selected_item_count . '");</script>';

        if ($selected_item_count > 0) {
            // check for existing cart
            $sql = "SELECT * FROM cart WHERE user_id = $_SESSION[user_id] AND deleted_at IS NULL";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 1) {
                // user has a cart
                $row = mysqli_fetch_assoc($result);
                $cart_id = $row['id'];

                // check if cart item that matches the relevant menu item exists in the cart
                $sql = "SELECT * FROM cart_item WHERE cart_id = $cart_id AND menu_item_id = $menu_item_id AND deleted_at IS NULL";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) == 1) {
                    // cart item exists in the cart
                    $sql = "UPDATE cart_item SET quantity = quantity + $selected_item_count WHERE cart_id = $cart_id AND menu_item_id = $menu_item_id";
                } else {
                    // cart item does not exist in the cart
                    $sql = "INSERT INTO cart_item (cart_id, menu_item_id, quantity) VALUES ($cart_id, $menu_item_id, $selected_item_count)";
                }

                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Product added successfully!');</script>";
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                // user has no cart
                $sql = "INSERT INTO cart (user_id) VALUES ({$_SESSION['user_id']})";

                // Execute the query and check if it was successful
                if (mysqli_query($conn, $sql)) {
                    $cart_id = mysqli_insert_id($conn);
                    $sql = "INSERT INTO cart_item (cart_id, menu_item_id, quantity) VALUES ($cart_id, $menu_item_id, $selected_item_count)";
                    $res = mysqli_query($conn, $sql);
                    echo "<script>alert('Product added successfully!');</script>";
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
            }
        }
    }
}



$query = <<< SQL
SELECT
	menu_item.id,
	menu_item.`name`,
	menu_item.`description`,
	menu_item.`price`,
	menu_item.`type`,
	menu_item.`category`,
	menu_item.`image`,
	menu.`name` AS menu_name,
	cuisine.`name` AS cuisine_type 
FROM
	menu_item
	INNER JOIN cuisine ON menu_item.cuisine_id = cuisine.id 
	INNER JOIN menu ON menu_item.menu_id = menu.id 
WHERE
	menu_item.id = $menu_item_id
	LIMIT 1;
SQL;

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $row['name'] ?> - The Gallery Café</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/menu-item.css">
    <link rel="shortcut icon" href="<?= BASE_URL ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <?php
    if ($row) {
        $menu_item_category = replaceUnderscoreWithSpaceAndConvertToCapitalCase($row['category']);
        $menu_item_type = replaceUnderscoreWithSpaceAndConvertToCapitalCase($row['type']);
        $menu_item_cuisine = replaceUnderscoreWithSpaceAndConvertToCapitalCase($row['cuisine_type']);

    ?>
        <div class="model-container menu-item-container-model">
            <div class="model menu-item-model">
                <div class="menu-item-image-container">
                    <img src="<?= BASE_URL ?>/public/images/menu-items/<?= $row['image']; ?>" alt="">
                </div>
                <div class="menu-item-info-and-actions-container">
                    <div class="menu-item-info-container">
                        <h4 class="menu-item-sub-title"><?= strtoupper($row['menu_name']); ?></h4>
                        <h2 class="menu-item-title"><?= $row['name']; ?></h2>
                        <b class="menu-item-price">LKR <?= $row['price']; ?></b>
                        <p class="menu-item-description"><?= $row['description']; ?></p>

                        <div class="menu-item-features-container">
                            <div class="menu-item-feature" title="Category: <?= $menu_item_category ?>">
                                <span class="material-symbols-rounded">category</span>
                                <span class="feature-text"><?= $menu_item_category ?></span>
                            </div>
                            <div class="menu-item-feature" title="Type: <?= $menu_item_type ?>">
                                <span class="material-symbols-rounded">fastfood</span>
                                <span class="feature-text"><?= $menu_item_type ?></span>
                            </div>
                            <div class="menu-item-feature" title="Cuisine: <?= $menu_item_cuisine ?>">
                                <span class="material-symbols-rounded">restaurant</span>
                                <span class="feature-text"><?= $menu_item_cuisine ?></span>
                            </div>
                        </div>
                    </div>

                    <form class="menu-item-actions-container" method="POST" action="<?= htmlspecialchars($_SERVER["REQUEST_URI"]); ?>">
                        <button type="submit" class="btn-primary">Add to Cart</button>
                        <div class="cart-items-incrementing-actions-container">
                            <button type="button" class="btn-secondary" onclick="decrementQuantity()"><span class="material-symbols-rounded">remove</span></button>
                            <input type="text" title="Cart Item Count" id="item-quantity" class="cart-item-count" name="selected_item_count" value="1" />
                            <button type="button" class="btn-secondary" onclick="incrementQuantity()"><span class="material-symbols-rounded">add</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <?php include('../../components/footer.php'); ?>

    <script src="<?= BASE_URL ?>/public/scripts/menu-item.js"></script>
</body>

</html>
