<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('ADMIN'));
$promotion_name = $promotion_description = $promotion_starts_at = $promotion_ends_at = $promotion_discount_percentage =  "";
$current_promotion_name = $current_promotion_description = $current_promotion_starts_at = $current_promotion_ends_at = $current_promotion_discount_percentage = "";
$promotion_name_error = $promotion_description_error = $promotion_starts_at_error = $promotion_ends_at_error = $promotion_discount_percentage_error = "";

$is_error = false;

if (!isset($_GET['promotion_id'])) {
    echo "Promotion ID not found";
    exit();
}
$promotion_id = sanitize_input($_GET['promotion_id']);

$q = <<<SQL
    SELECT * FROM `promotion` WHERE `id` = {$_GET['promotion_id']} LIMIT 1;
SQL;

$result = mysqli_query($conn, $q);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
} else {
    $promotion = mysqli_fetch_assoc($result);

    if (!$promotion) {
        echo "Promotion not found";
        exit();
    } else {
        $current_promotion_name = $promotion_name = $promotion['name'];
        $current_promotion_description = $promotion_description = $promotion['description'];
        $current_promotion_starts_at = $promotion_starts_at = $promotion['starts_at'];
        $current_promotion_ends_at = $promotion_ends_at = $promotion['ends_at'];
        $current_promotion_discount_percentage = $promotion_discount_percentage = $promotion['discount_percentage'];
    }
}

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $promotion_name = sanitize_input($_POST["name"]);
    if (empty($_POST["name"])) {
        $name_error = "Name cannot be empty";
        $is_error = true;
    }

    $promotion_description = sanitize_input($_POST["description"]);
    if (empty($_POST["description"])) {
        $promotion_description_error = "Description cannot be empty";
        $is_error = true;
    }

    $promotion_starts_at  = sanitize_input($_POST["starts_at"]);
    $promotion_ends_at = sanitize_input($_POST["ends_at"]);
    if (!empty($_POST["starts_at"]) && !empty($_POST["ends_at"]) && $promotion_starts_at > $promotion_ends_at) {
        $is_error = true;
        $promotion_ends_at_error = "End date cannot be before start date";
    }

    $promotion_discount_percentage = sanitize_input($_POST["discount_percentage"]);
    if (empty($_POST["discount_percentage"])) {
        $promotion_discount_percentage_error = "Discount percentage cannot be empty";
        $is_error = true;
    } else {
        $promotion_discount_percentage = floatval($promotion_discount_percentage) / 100;
    }



    if (!$is_error) {
        $query = "UPDATE `promotion` SET `name` = '$promotion_name', `description` = '$promotion_description', `starts_at` = '$promotion_starts_at', `ends_at` = '$promotion_ends_at', `discount_percentage` = '$promotion_discount_percentage',  `updated_at` = NOW() WHERE `id` = {$promotion_id}";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Promotion updated successfully!');</script>";
            header("Location: " . BASE_URL . "/pages/dashboard/admin/promotions/view_promotions.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Promotion #<?= $promotion_id ?> - The Gallery Café</title>
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
                <div class="dashboard-content admin-dashboard-promotions">
                    <header>
                        <h2>Add New Promotion #<?= $promotion_id ?></h2>
                    </header>

                    <form class="register-form" method="POST" action="<?= htmlspecialchars($_SERVER["REQUEST_URI"]); ?>">
                        <div class="input-container">
                            <label for="name">Name *</label>
                            <input type="text" name="name" id="name" placeholder="Christmas Promotion" value="<?= $promotion_name ?>" required />
                            <span class="error-message"><?= $promotion_name_error; ?></span>
                        </div>

                        <div class="input-container">
                            <label for="description">Description *</label>
                            <textarea name="description" id="description" placeholder="What's new in Christmas" maxlength="1000"><?= $promotion_description ?></textarea>
                            <span class="error-message"><?= $promotion_description_error; ?></span>
                            <small>Maximum 1000 characters</small>
                        </div>

                        <div class="input-group-container">
                            <div class="input-container">
                                <label for="discount_percentage">Discount Percentage % *</label>
                                <input type="number" name="discount_percentage" id="discount_percentage" placeholder="1000.00" value="<?= floatval($promotion_discount_percentage) * 100 ?>" required />
                                <span class="error-message"><?php echo $promotion_discount_percentage_error; ?></span>
                            </div>
                        </div>

                        <div class="input-group-container">
                            <div class="input-container">
                                <label for="starts_at">Starts At *</label>
                                <input type="datetime-local" name="starts_at" id="starts_at" value="<?= $promotion_starts_at ?>" required />
                                <span class="error-message"><?= $promotion_starts_at_error; ?></span>
                            </div>
                            <div class="input-container">
                                <label for="ends_at">Ends At *</label>
                                <input type="datetime-local" name="ends_at" id="ends_at" value="<?= $promotion_ends_at ?>" required />
                                <span class="error-message"><?= $promotion_ends_at_error; ?></span>
                            </div>
                        </div>
                        <hr>

                        <button class="btn-primary form-submit-btn" type="submit">Update Promotion</button>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include('../../../../components/footer.php'); ?>
</body>

</html>
