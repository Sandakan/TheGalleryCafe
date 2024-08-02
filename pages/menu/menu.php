<?php
require '../../config.php';
require '../../utils/database.php';
require '../../components/menu_item.php';

$conn = initialize_database();
session_start();

function displayMenuItems(string $menu_id, $conn)
{
    $sql = "SELECT id, name, description, price, image FROM menu_item WHERE menu_id = $menu_id";

    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) > 0) {
        // Loop through each result and display in table rows
        while ($row = mysqli_fetch_assoc($result)) {
            echo renderMenuItem(intval($row['id']), $row['name'], $row['price'], BASE_URL . '/public/images/menu-items/' . $row['image']);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Menu Today - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/menu.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.png" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <section class="menus-container">
        <div class="menu-info">
            <h3>Our Menu Today</h3>
            <p>Whether you're in the mood for a hearty breakfast, a leisurely lunch, or an exquisite dinner, our featured menus offer something to satisfy every palate. Join us and savor the exceptional cuisine that defines The Gallery Café experience.</p>
        </div>

        <?php
        $sql = "SELECT id, name, description FROM menu";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $menu_name = $row['name'];
                $menu_description = $row['description'];


                echo <<< HTML
                <div class="menu-category">
                    <div class="menu-category-info">
                        <h4 class="menu-category-title">$menu_name</h4>
                        <h4 class="menu-category-description">$menu_description</h4>
                    </div>
                    <div class="menus">
                HTML;

                displayMenuItems(intval($row['id']), $conn);

                echo <<< HTML
                    </div>
                </div>
                HTML;
            }
        }
        ?>

    </section>

    <?php
    include('../../components/footer.php');
    mysqli_close($conn);
    ?>
</body>

</html>
