<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('ADMIN'));
$menu_name = $menu_description = "";
$menu_name_error = $menu_description_error = "";

$is_error = false;

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_name = sanitize_input($_POST["menu_name"]);
    if (!empty($_POST["menu_name"]) && !preg_match("/^[a-zA-Z ]+$/", $menu_name)) {
        $menu_name_error = "Only letters and white space allowed";
        $is_error = true;
    }

    $menu_description = sanitize_input($_POST["menu_description"]);
    if (empty($_POST["menu_description"])) {
        $menu_description_error = "Menu description cannot be empty";
        $is_error = true;
    }

    if (!$is_error) {
        $query = "INSERT INTO menu (name, description) VALUES ('$menu_name', '$menu_description')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Menu added successfully!');</script>";
            header("Location: " . BASE_URL . "/pages/dashboard/admin/menus/view_menus.php");
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
    <title>Add New Menu - The Gallery Café</title>
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
                <div class="dashboard-content admin-dashboard-menus">
                    <header>
                        <h2>Add New Menu</h2>
                    </header>

                    <form class="register-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="input-container">
                            <label for="menu_name">Menu Name *</label>
                            <input type="text" name="menu_name" id="menu_name" placeholder="Breakfast Menu" value="<?= $menu_name ?>" required />
                            <span class="error-message"><?php echo $menu_name_error; ?></span>
                        </div>

                        <div class="input-container">
                            <label for="menu_description">Menu Description *</label>
                            <textarea name="menu_description" id="menu_description" placeholder="My Lovely breakfast menu" maxlength="1000"><?= $menu_description ?></textarea>
                            <span class="error-message"><?php echo $menu_description_error; ?></span>
                            <small>Maximum 1000 characters</small>
                        </div>
                        <hr>

                        <button class="btn-primary form-submit-btn" type="submit">Add Menu</button>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include('../../../../components/footer.php'); ?>
</body>

</html>
