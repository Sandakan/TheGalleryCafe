<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('STAFF'));
$current_reservation_no_of_people =
    $current_reservation_occasion =
    $current_reservation_special_request =
    $current_reservation_pre_order_id =
    $current_reservation_start_time =
    $current_reservation_end_time =
    $current_reservation_table_id = '';
$reservation_no_of_people =
    $reservation_occasion =
    $reservation_special_request =
    $reservation_pre_order_id =
    $reservation_start_time =
    $reservation_end_time =
    $reservation_table_id = '';
$reservation_no_of_people_error =
    $reservation_occasion_error =
    $reservation_special_request_error =
    $reservation_pre_order_id_error =
    $reservation_start_time_error =
    $reservation_end_time_error =
    $reservation_table_id_error = '';

$current_reservation_date;
$is_error = false;
$occasions = array('Birthday', 'Anniversary', 'Wedding', 'Other');


if (!isset($_GET['reservation_id'])) {
    echo "Menu ID not found";
    exit();
}
$reservation_id = $_GET['reservation_id'];

$q =
    <<<SQL
SELECT
    r.id AS reservation_id,
    r.no_of_people AS reservation_no_of_people,
    r.occasion AS reservation_occasion,
    r.special_request AS reservation_special_request,
    o.id AS reservation_pre_order_id,
    tr.starts_at AS reservation_start_time,
    tr.ends_at AS reservation_end_time,
    tr.table_id AS reservation_table_id
FROM
    `reservation` r
LEFT JOIN `table_reservation` tr ON r.table_reservation_id = tr.id
LEFT JOIN `order` o ON o.reservation_id = r.id
WHERE 
    r.id = $reservation_id
ORDER BY
    DATEDIFF(tr.starts_at, NOW()) DESC;
SQL;

$result = mysqli_query($conn, $q);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
} else {
    $menu = mysqli_fetch_assoc($result);

    if (!$menu) {
        echo "Menu not found";
        exit();
    } else {
        $reservation_id = $menu['reservation_id'];
        $current_reservation_no_of_people = $reservation_no_of_people = $menu['reservation_no_of_people'];
        $current_reservation_occasion = $reservation_occasion = $menu['reservation_occasion'];
        $current_reservation_special_request = $reservation_special_request = $menu['reservation_special_request'];
        $current_reservation_pre_order_id = $reservation_pre_order_id = $menu['reservation_pre_order_id'];
        $current_reservation_start_time = $reservation_start_time = $menu['reservation_start_time'];
        $current_reservation_end_time = $reservation_end_time = $menu['reservation_end_time'];
        $current_reservation_table_id = $reservation_table_id = $menu['reservation_table_id'];
        $current_reservation_date = date('Y-m-d', strtotime($current_reservation_start_time));
    }
}

// get max number of people
$q2 = "SELECT MAX(DISTINCT capacity) AS max_no_of_people FROM `table` ORDER BY capacity ASC";
$res2 = mysqli_query($conn, $q2);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

$max_no_of_people = mysqli_fetch_assoc($res2)['max_no_of_people'];


function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function fetchAvailableTable($conn, $reservation_datetime, $no_of_people)
{
    $q1 = <<< SQL
        SELECT
            t.id AS table_id, t.capacity
        FROM `table` t
            LEFT JOIN table_reservation tr ON t.id = tr.table_id 
            -- checks whether the reservation is in the same date
            AND DATE(tr.starts_at) = DATE('$reservation_datetime')
            -- checks whether the reservation starts before or at the reservation_datetime
            AND TIME(tr.starts_at) <= TIME('$reservation_datetime') 
            -- checks whether the reservation ends after the reservation_datetime
            AND (TIME(tr.ends_at) > TIME('$reservation_datetime'))
        WHERE
            -- checks for a table has atleast the required capacity
            t.capacity >= $no_of_people 
            -- checks whether the table is not reserved and not deleted
            AND (tr.id IS NULL OR tr.deleted_at IS NOT NULL)
        ORDER BY 
            t.capacity ASC, t.id ASC
        LIMIT 1;
    SQL;

    // // Prepare the statement
    $res = mysqli_query($conn, $q1);
    $available_table = mysqli_fetch_assoc($res);

    return $available_table ? $available_table['table_id'] : null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_of_people = $_POST["no_of_people"];
    // reservation_time contains both date and time
    $reservation_time = $_POST["reservation_time"];
    $occasion = $_POST["occasion"];
    $special_request = $_POST["special_request"];

    $reservation_start_datetime = date('Y-m-d H:i:s', strtotime($reservation_time));
    $reservation_end_time = strtotime($reservation_time) + (SLOT_DURATION * 60);
    $reservation_end_datetime = date('Y-m-d H:i:s', $reservation_end_time);

    $table_id = fetchAvailableTable($conn, $reservation_start_datetime, $no_of_people);

    if ($table_id) {
        // create a new table reservation entry
        $q2 = <<< SQL
            UPDATE 
                table_reservation 
            SET 
                table_id = $table_id, 
                starts_at = '$reservation_start_datetime', 
                ends_at = '$reservation_end_datetime', 
                updated_at = NOW() 
            WHERE 
                id = (SELECT table_reservation_id FROM reservation WHERE id = $reservation_id);
        SQL;

        if (!mysqli_query($conn, $q2)) {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            exit();
        }

        // create a new reservation entry
        $q3 = <<< SQL
                UPDATE 
                    reservation 
                SET 
                    no_of_people = $no_of_people,
                    occasion = '$occasion',
                    special_request = '$special_request',
                    updated_at = NOW()
                WHERE 
                    id = $reservation_id;
            SQL;

        if (!mysqli_query($conn, $q3)) {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            exit();
        }

        $reservation_id = mysqli_insert_id($conn);

        echo "<script>alert('Reservation created successfully!');</script>";
        header("Location: " . BASE_URL . "/pages/dashboard/staff/reservations/view_reservations.php");
        exit();
    } else echo "<script>alert('No available table found!');</script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta menu_name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editing Reservation #<?php echo $reservation_id ?> - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/dashboard.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <h1>Staff Dashboard</h1>
    <?php include('../../../../components/header_navigation_bar.php'); ?>

    <main>
        <div class="dashboard-container">

            <?php include('../../../../components/staff_dashboard_side_nav.php'); ?>

            <div class="dashboard-content-container">
                <div class="dashboard-content staff-dashboard-menus">
                    <header>
                        <h2>Editing Reservation #<?php echo $reservation_id ?></h2>
                    </header>

                    <?php if (!empty($current_reservation_pre_order_id)) { ?>
                        <div class="alert">
                            <div class="alert-heading">
                                <span class="material-symbols-rounded">info</span>
                                <h4>This reservation has a pre-order #<?= $current_reservation_pre_order_id ?></h4>
                            </div>
                            <div class="alert-content">
                                <p class="alert-text">A pre-order #<?= $current_reservation_pre_order_id ?> has been created for this reservation. Go to Orders tab to view and edit the pre-order linked to this reservation.</p>
                                <a href="<?= BASE_URL; ?>/pages/dashboard/staff/orders/edit_order?order_id=<?= $current_reservation_pre_order_id; ?>.php" class="btn-secondary">Edit Order #<?= $current_reservation_pre_order_id ?></a>
                            </div>
                        </div>
                    <?php } ?>

                    <form class="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>">
                        <div class="input-group-container">
                            <div class="input-container">
                                <label for="no_of_people">Number of people *</label>
                                <select name="no_of_people" id="no_of_people" required onchange="getReservationTimeSlots('<?= BASE_URL ?>')">
                                    <option value="" disabled hidden>Select the number of people</option>
                                    <?php

                                    for ($i = 1; $i <= $max_no_of_people; $i++) {
                                        $selected = ($i == $current_reservation_no_of_people) ? "selected" : "";
                                        if ($i == 1) echo "<option value='$i' $selected>$i person</option>";
                                        else echo "<option value='$i' $selected>$i people</option>";
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="input-container">
                                <label for="reservation_date">Reservation Date *</label>
                                <input type="date" name="reservation_date" id="reservation_date" required onchange="getReservationTimeSlots('<?= BASE_URL ?>')" value="<?php echo $current_reservation_date; ?>" />
                            </div>

                            <div class="input-container">
                                <label for="reservation_time">Reservation Time *</label>
                                <select type="text" name="reservation_time" id="reservation_time" placeholder="Select your preferred reservation time" required>
                                    <option value="" selected disabled hidden>Select your preferred reservation time</option>
                                </select>
                                <small id="find-table-model-response-container">Select previous options to list the available time slots</small>
                            </div>
                        </div>

                        <hr>

                        <div class="input-group-container">
                            <div class="input-container">
                                <label for="occasion">Select an occasion (optional)</label>
                                <select type="text" name="occasion" id="occasion" placeholder="Select an occasion">
                                    <?php
                                    foreach ($occasions as $occasion) {
                                        $selected =  $occasion == $current_reservation_occasion  ? 'selected' : '';
                                        echo '<option value="' . $occasion . '" ' . $selected . '>' . $occasion . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="input-container">
                                <label for="special_request">Add a special request (optional)</label>
                                <input type="text" name="special_request" id="special_request" placeholder="Play a song by Taylor Swift" value="<?= $current_reservation_special_request; ?>" />
                            </div>
                        </div>

                        <hr>

                        <button class="btn-primary form-submit-btn" type="submit">Update Reservation</button>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php
    include('../../../../components/footer.php');
    echo '<script src="' . BASE_URL . '/public/scripts/reservations.js"></script>';
    ?>
</body>

</html>
