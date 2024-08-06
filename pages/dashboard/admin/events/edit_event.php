<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('ADMIN'));
$event_name = $event_description = $event_starts_at = $event_ends_at = "";
$current_event_name = $current_event_description = $current_event_starts_at = $current_event_ends_at = "";
$event_name_error = $event_description_error = $event_starts_at_error = $event_ends_at_error = "";

$is_error = false;

if (!isset($_GET['event_id'])) {
    echo "Event ID not found";
    exit();
}
$event_id = sanitize_input($_GET['event_id']);

$q = <<<SQL
    SELECT * FROM `event` WHERE `id` = {$_GET['event_id']} LIMIT 1;
SQL;

$result = mysqli_query($conn, $q);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
} else {
    $event = mysqli_fetch_assoc($result);

    if (!$event) {
        echo "Event not found";
        exit();
    } else {
        $current_event_name = $event_name = $event['name'];
        $current_event_description = $event_description = $event['description'];
        $current_event_starts_at = $event_starts_at = $event['starts_at'];
        $current_event_ends_at = $event_ends_at = $event['ends_at'];
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
    $event_name = sanitize_input($_POST["name"]);
    if (!empty($_POST["name"]) && !preg_match("/^[a-zA-Z ]+$/", $event_name)) {
        $name_error = "Only letters and white space allowed";
        $is_error = true;
    }

    $event_description = sanitize_input($_POST["description"]);
    if (empty($_POST["description"])) {
        $event_description_error = "Description cannot be empty";
        $is_error = true;
    }

    $event_starts_at  = sanitize_input($_POST["starts_at"]);
    $event_ends_at = sanitize_input($_POST["ends_at"]);

    if (!empty($_POST["starts_at"]) && !empty($_POST["ends_at"]) && $event_starts_at > $event_ends_at) {
        $is_error = true;
        $event_ends_at_error = "End date cannot be before start date";
    }


    if (!$is_error) {
        $query = "UPDATE `event` SET `name` = '$event_name', `description` = '$event_description', `starts_at` = '$event_starts_at', `ends_at` = '$event_ends_at', `updated_at` = NOW() WHERE `id` = {$event_id}";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Event updated successfully!');</script>";
            header("Location: " . BASE_URL . "/pages/dashboard/admin/events/view_events.php");
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
    <title>Update Event #<?= $event_id ?> - The Gallery Café</title>
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
                <div class="dashboard-content admin-dashboard-events">
                    <header>
                        <h2>Add New Event #<?= $event_id ?></h2>
                    </header>

                    <form class="register-form" method="POST" action="<?= htmlspecialchars($_SERVER["REQUEST_URI"]); ?>">
                        <div class="input-container">
                            <label for="name">Name *</label>
                            <input type="text" name="name" id="name" placeholder="Christmas Event" value="<?= $event_name ?>" required />
                            <span class="error-message"><?= $event_name_error; ?></span>
                        </div>

                        <div class="input-container">
                            <label for="description">Description *</label>
                            <textarea name="description" id="description" placeholder="What's new in Christmas" maxlength="1000"><?= $event_description ?></textarea>
                            <span class="error-message"><?= $event_description_error; ?></span>
                            <small>Maximum 1000 characters</small>
                        </div>

                        <div class="input-group-container">
                            <div class="input-container">
                                <label for="starts_at">Starts At *</label>
                                <input type="datetime-local" name="starts_at" id="starts_at" value="<?= $event_starts_at ?>" required />
                                <span class="error-message"><?= $event_starts_at_error; ?></span>
                            </div>
                            <div class="input-container">
                                <label for="ends_at">Ends At *</label>
                                <input type="datetime-local" name="ends_at" id="ends_at" value="<?= $event_ends_at ?>" required />
                                <span class="error-message"><?= $event_ends_at_error; ?></span>
                            </div>
                        </div>
                        <hr>

                        <button class="btn-primary form-submit-btn" type="submit">Update Event</button>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include('../../../../components/footer.php'); ?>
</body>

</html>
