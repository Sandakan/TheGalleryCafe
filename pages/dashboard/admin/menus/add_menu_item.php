<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('ADMIN'));
$menu_item_name = $menu_item_description = $menu_item_price = $menu_item_category = $menu_item_type = $menu_item_image = $menu_item_cuisine_type = "";
$menu_item_name_error = $menu_item_description_error = $menu_item_price_error = $menu_item_category_error = $menu_item_type_error = $menu_item_image_error = $menu_item_cuisine_type_error = "";

$is_error = false;
$menu_item_image_target_dir = './../../../../public/images/menu-items/';

if (!isset($_GET['menu_id'])) {
    echo "Menu ID not found";
    exit();
}
$menu_id = $_GET['menu_id'];

$menu_item_types = array('MEAL', 'BEVERAGE', 'SPECIAL');
$menu_item_categories = array('APPETIZER', 'DESSERT', 'MAIN_COURSE', 'SIDE_DISH');
$cuisine_types = array();

$q1 = <<< SQL
    SELECT 
        id, `name` 
    FROM 
        cuisine;
SQL;

$res1 = mysqli_query($conn, $q1);
while ($row = mysqli_fetch_assoc($res1)) {
    array_push($cuisine_types, $row);
}

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

function getCuisineTypeName($cuisine)
{
    return $cuisine['name'];
}

$cuisine_type_names = array_map("getCuisineTypeName", $cuisine_types);


function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_item_name = sanitize_input($_POST["menu_item_name"]);
    if (!empty($_POST["menu_item_name"]) && !preg_match("/^[a-zA-Z ]+$/", $menu_item_name)) {
        $menu_item_name_error = "Only letters and white space allowed";
        $is_error = true;
    }

    $menu_item_description = sanitize_input($_POST["menu_item_description"]);
    if (empty($_POST["menu_item_description"])) {
        $menu_item_description_error = "Menu item description cannot be empty";
        $is_error = true;
    }

    $menu_item_price = sanitize_input($_POST["menu_item_price"]);
    if (!empty($_POST["menu_item_price"]) && !preg_match("/^[0-9]+(.?\d{2})?$/", $menu_item_price)) {
        $menu_item_price_error = "Only numbers allowed";
        $is_error = true;
    }

    $menu_item_category = sanitize_input($_POST["menu_item_category"]);
    if (!in_array($menu_item_category, $menu_item_categories)) {
        $menu_item_category_error = "Invalid menu item category";
        $is_error = true;
    }

    $menu_item_type = sanitize_input($_POST["menu_item_type"]);
    if (!in_array($menu_item_type, $menu_item_types)) {
        $menu_item_type_error = "Invalid menu item type";
        $is_error = true;
    }

    $menu_item_cuisine_type = sanitize_input($_POST["menu_item_cuisine_type"]);
    if (!in_array($menu_item_cuisine_type, $cuisine_type_names)) {
        $menu_item_cuisine_type_error = "Invalid cuisine type";
        $is_error = true;
    }

    if (isset($_FILES["menu_item_image"]) && !empty($_FILES["menu_item_image"]["tmp_name"])) {
        // check the image file mime type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['menu_item_image']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ),
            true
        )) {
            $menu_item_image_error = "Only JPG, PNG, WEBP and GIF files are allowed";
            $is_error = true;
        }
    }

    if (!$is_error) {
        $query = <<< SQL
        INSERT INTO menu_item
            (menu_id, name, description, price, category, type, cuisine_id, image)
        VALUES
            (
                '$menu_id', 
                '$menu_item_name', 
                '$menu_item_description',
                '$menu_item_price', 
                '$menu_item_category', 
                '$menu_item_type', 
                (SELECT id FROM cuisine WHERE name = '$menu_item_cuisine_type'),
                '$menu_item_image'
            );
        SQL;

        if (mysqli_query($conn, $query)) {
            $menu_item_id = mysqli_insert_id($conn);

            if (isset($_FILES["menu_item_image"]) && !empty($_FILES["menu_item_image"]["tmp_name"])) {
                $menu_item_image_file = $_FILES["menu_item_image"]["name"];
                $menu_item_image_ext = '.' . pathinfo($menu_item_image_file, PATHINFO_EXTENSION);
                $menu_item_image_new_filename = $menu_item_id .  $menu_item_image_ext;
                $menu_item_image_save_path = $menu_item_image_target_dir . $menu_item_image_new_filename;


                if (!move_uploaded_file(
                    $_FILES['menu_item_image']['tmp_name'],
                    $menu_item_image_save_path
                )) {
                    throw new RuntimeException('Failed to move uploaded file.');
                }

                echo 'File is uploaded successfully.';
                $menu_item_image = $menu_item_image_new_filename;
            }

            $query = <<< SQL
            UPDATE menu_item
            SET image = '$menu_item_image'
            WHERE id = '$menu_item_id';
            SQL;
            mysqli_query($conn, $query);

            echo "<script>alert('Menu Item added successfully!');</script>";
            header("Location: " . BASE_URL . "/pages/dashboard/admin/menus/view_menu_items.php?menu_id=" . $menu_id);
            exit();
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta menu_name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add menu item to '<?php echo $menu['name']; ?>' - The Gallery Café</title>
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
                        <h2>Add menu item to '<?php echo $menu['name']; ?>'</h2>
                    </header>

                    <form class="register-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" enctype="multipart/form-data">
                        <div class="input-group-container">
                            <img id="display_image" src="" style="display:none;" alt="Menu Item Image">
                            <div class="input-container">
                                <label for="menu_item_image">Image *</label>
                                <input type="file" name="menu_item_image" id="menu_item_image" placeholder="French Toast" onchange="showMenuItemImage()" />
                                <span class="error-message"><?php echo $menu_item_image_error; ?></span>
                            </div>
                        </div>

                        <div class="input-container">
                            <label for="menu_item_name">Name *</label>
                            <input type="text" name="menu_item_name" id="menu_item_name" placeholder="French Toast" value="<?= $menu_item_name ?>" required />
                            <span class="error-message"><?php echo $menu_item_name_error; ?></span>
                        </div>

                        <div class="input-container">
                            <label for="menu_item_description">Description *</label>
                            <textarea name="menu_item_description" id="menu_item_description" placeholder="My Lovely French Toast" maxlength="1000"><?= $menu_item_description ?></textarea>
                            <span class="error-message"><?php echo $menu_item_description_error; ?></span>
                            <small>Maximum 1000 characters</small>
                        </div>


                        <div class="input-group-container">
                            <div class="input-container">
                                <label for="menu_item_category">Category *</label>
                                <select name="menu_item_category" id="menu_item_category" required>
                                    <option value="" selected disabled hidden>Select Category</option>
                                    <?php
                                    foreach ($menu_item_categories as $category) {
                                        $selected = ($menu_item_category == $category) ? 'selected' : '';
                                        echo '<option value="' . $category . '" ' . $selected . '>' . $category . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="error-message"><?php echo $menu_item_category_error; ?></span>
                            </div>
                            <div class="input-container">
                                <label for="menu_item_type">Type *</label>
                                <select name="menu_item_type" id="menu_item_type" required>
                                    <option value="" selected disabled hidden>Select Meal Type</option>
                                    <?php
                                    foreach ($menu_item_types as $type) {
                                        $selected = ($menu_item_type == $type) ? 'selected' : '';
                                        echo '<option value="' . $type . '" ' . $selected . '>' . $type . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="error-message"><?php echo $menu_item_type_error; ?></span>
                            </div>
                            <div class="input-container">
                                <label for="menu_item_cuisine_type">Cuisine Type *</label>
                                <select name="menu_item_cuisine_type" id="menu_item_cuisine_type" required>
                                    <option value="" selected disabled hidden>Select Cuisine Type</option>
                                    <?php
                                    foreach ($cuisine_types as $cuisine_type) {
                                        $selected = ($cuisine_type['name'] == $menu_item_cuisine_type) ? 'selected' : '';
                                        echo '<option value="' . $cuisine_type['name'] . '" ' . $selected . '>' . $cuisine_type['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="error-message"><?php echo $menu_item_cuisine_type_error; ?></span>
                            </div>
                        </div>
                        <hr>

                        <div class="input-group-container">
                            <div class="input-container">
                                <label for="menu_item_price">Price *</label>
                                <input type="number" name="menu_item_price" id="menu_item_price" placeholder="1000.00" value="<?= $menu_item_price ?>" required />
                                <span class="error-message"><?php echo $menu_item_price_error; ?></span>
                            </div>
                        </div>

                        <hr>

                        <button class="btn-primary form-submit-btn" type="submit">Update Menu Item</button>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include('../../../../components/footer.php');
    echo "<script src='" . BASE_URL . "/public/scripts/add_menu_item.js'></script>";
    ?>
</body>

</html>
