<?php
require '../../config.php';
require '../../utils/database.php';

$conn = initialize_database();
session_start();

if (!isset($_SESSION["user_id"])) {
    $currentUrl = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    header("Location: " . BASE_URL . "/pages/auth/login.php?redirect=" . urlencode($currentUrl));
    exit();
}
$user_id = $_SESSION["user_id"];
$BASE_URL = BASE_URL;
// reservation info
$no_of_people = 0;
$reservation_date;
$reservation_time;
$table_id;



// get max number of people
$q = "SELECT MAX(DISTINCT capacity) AS max_no_of_people FROM `table` ORDER BY capacity ASC";
$result = mysqli_query($conn, $q);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

$max_no_of_people = mysqli_fetch_assoc($result)['max_no_of_people'];

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
    $occasion = $_POST["occasion"] ?? null;
    $special_request = $_POST["special_request"] ?? null;
    $pre_orders = $_POST["pre_orders"];

    $is_pre_orders_enabled = $pre_orders == "enabled";
    $reservation_start_datetime = date('Y-m-d H:i:s', strtotime($reservation_time));
    $reservation_end_time = strtotime($reservation_time) + (SLOT_DURATION * 60);
    $reservation_end_datetime = date('Y-m-d H:i:s', $reservation_end_time);

    $table_id = fetchAvailableTable($conn, $reservation_start_datetime, $no_of_people);

    if ($table_id) {
        // create a new table reservation entry
        $q2 = <<< SQL
            INSERT INTO table_reservation (table_id, starts_at, ends_at)
            VALUES (?, ?, ?);
        SQL;

        // using prepared statements to handle nullable values such as occasion and special_request
        $stmt = mysqli_prepare($conn, $q2);
        mysqli_stmt_bind_param($stmt, "iss", $table_id, $reservation_start_datetime, $reservation_end_datetime);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_error($stmt)) {
            echo "<script>alert('Error: " . mysqli_stmt_error($stmt) . "');</script>";
            exit();
        }
        $table_reservation_id = mysqli_insert_id($conn);

        // create a new reservation entry
        $q3 = <<< SQL
                INSERT INTO reservation (table_reservation_id, user_id, occasion, special_request, no_of_people)
                VALUES ($table_reservation_id, $user_id, '$occasion', '$special_request', $no_of_people);
            SQL;

        if (!mysqli_query($conn, $q3)) {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            exit();
        }

        $reservation_id = mysqli_insert_id($conn);

        if ($is_pre_orders_enabled) {
            // get cart items
            $q4 = <<< SQL
                        SELECT 
                            ci.menu_item_id, ci.quantity, mi.price 
                        FROM cart_item ci 
                        INNER JOIN 
                            cart c ON ci.cart_id = c.id 
                        INNER JOIN 
                            menu_item mi ON ci.menu_item_id = mi.id 
                        WHERE 
                        c.user_id = $user_id AND c.deleted_at IS NULL AND ci.deleted_at IS NULL;
                    SQL;

            $res = mysqli_query($conn, $q4);
            if (!$res) {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                exit();
            }

            $total_amount = 0;
            $cart_items = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $cart_items[] = $row;
                $total_amount += $row['quantity'] * $row['price'];
            }

            if (empty($cart_items)) {
                echo "<script>alert('You selected to add the cart items as a pre-order. Please add items to cart first.');</script>";
                exit();
            }

            // create a new order with the total amount and the reservation id
            $q5 = <<< SQL
                INSERT INTO `order` (user_id, reservation_id, total_amount)
                VALUES ($user_id, $reservation_id, $total_amount);
            SQL;
            if (!mysqli_query($conn, $q5)) {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                exit();
            }

            $order_id = mysqli_insert_id($conn);

            // add each cart item as order items
            foreach ($cart_items as $cart_item) {
                $q5 = <<< SQL
                    INSERT INTO order_item (order_id, menu_item_id, quantity)
                    VALUES ($order_id, {$cart_item['menu_item_id']}, {$cart_item['quantity']});
                SQL;

                if (!mysqli_query($conn, $q5)) {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                    exit();
                };
            }

            // delete cart
            $q6 = <<< SQL
                UPDATE cart SET deleted_at = NOW()
                WHERE user_id = $user_id 
                AND deleted_at IS NULL;
            SQL;
            if (!mysqli_query($conn, $q6)) {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                exit();
            }
        }
        echo "<script>alert('Reservation created successfully!');</script>";
        header("Location: " . BASE_URL . "/pages/reservations/reservation_placed.php");
        exit();
    } else echo "<script>alert('No available table found!');</script>";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reservations - The Gallery Café</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/fonts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles/reservations.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="model-container reservation-model-container">

        <div class="reservation-info-container">
            <h1 class="create-reservation-heading">Create A Reservation</h1>
            <p>
                Creating a reservation at The Gallery Café ensures that you have a guaranteed spot in our popular restaurant, eliminating the wait time and enhancing your dining experience. Reservations help us prepare for your visit, ensuring that our team can provide you with the best service and culinary delights that our café is known for.
            </p>
        </div>

        <div class="model reservation-model find-table-model">
            <h2 class="create-reservation-heading">1. Find a table</h2>
            <p>Select a table that suits your needs. Please note that a selected table will be held for you upto 5 minutes until you complete the reservation.</p>

            <input type="hidden" name="reason" value="find-table">
            <div class="input-group-container">
                <div class="input-container">
                    <label for="no_of_people">Number of people *</label>
                    <select type="text" name="no_of_people" id="no_of_people" placeholder="Select the no of people" required onchange="getReservationTimeSlots('<?= BASE_URL ?>')">
                        <option value="" selected disabled hidden>Select the number of people</option>
                        <?php

                        for ($i = 1; $i <= $max_no_of_people; $i++) {
                            if ($i == 1) echo "<option value='$i'>$i person</option>";
                            else echo "<option value='$i'>$i people</option>";
                        }
                        ?>
                    </select>
                </div>


                <div class="input-container">
                    <label for="reservation_date">Reservation Date *</label>
                    <input type="date" name="reservation_date" id="reservation_date" required onchange="getReservationTimeSlots('<?= BASE_URL ?>')" />
                </div>

                <div class="input-container">
                    <label for="reservation_time">Reservation Time *</label>
                    <select type="text" name="reservation_time" id="reservation_time" placeholder="Select your preferred reservation time" required>
                        <option value="" selected disabled hidden>Select your preferred reservation time</option>
                    </select>
                    <small id="find-table-model-response-container">Select previous options to list the available time slots</small>
                </div>
            </div>
        </div>

        <div class="model reservation-model enter-details-model">
            <h2 class="create-reservation-heading">2. Enter your details</h2>
            <p>Enter your details below about the reservation you wish to make.</p>

            <div class="logged-in-account">
                <b>Using logged in account</b>

                <div class="account">
                    <div class="account-info"><span class="material-symbols-rounded-filled">account_circle</span> <?php echo $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']; ?></div>
                    <button type="submit" class="btn-secondary" onclick="logout('<?= BASE_URL ?>')" title="Create reservation using a different account (Will be logged out automatically)">Use another account</button>

                </div>
            </div>

            <div class="input-group-container">
                <div class="input-container">
                    <label for="occasion">Select an occasion (optional)</label>
                    <select type="text" name="occasion" id="occasion" placeholder="Select an occasion">
                        <option value="" selected disabled hidden>Select an occasion (optional)</option>
                        <option value="Birthday">Birthday</option>
                        <option value="Anniversary">Anniversary</option>
                        <option value="Meeting">Meeting</option>
                    </select>
                </div>

                <div class="input-container">
                    <label for="special_request">Add a special request (optional)</label>
                    <input type="text" name="special_request" id="special_request" placeholder="Play a song by Taylor Swift" />
                </div>
            </div>
        </div>

        <div class="model reservation-model pre-order-items-model">
            <h2 class="create-reservation-heading">3. Pre-order menu items for reservation</h2>
            <p>Please select the menu items you wish to pre-order for the reservation. Pre-ordered menu items will be delivered to you at the start of the reservation with minimal delay.</p>

            <div class="options-container">
                <label class="option" for="pre-orders-enabled">
                    <h3>Pre-order <?= $cart_items_count ?> cart items for reservation</h3>
                    <input type="radio" name="pre_orders" id="pre-orders-enabled" value="enabled" required />
                </label>

                <label class="option" for="pre-orders-disabled">
                    <h3>Order items at the reservation</h3>
                    <input type="radio" name="pre_orders" id="pre-orders-disabled" value="disabled" required />
                </label>
            </div>
        </div>

        <div class="model reservation-model confirm-model">
            <h2 class="create-reservation-heading">4. Confirm reservation</h2>
            <p>Please make sure that all the information provided are correct to provide you the best experience and to provide you with an exceptional service.</p>
            <p>We may contact you about this reservation, so keep the email and the contact number you provided free and available.</p>
            <p>We have a 15 minute grace period. Please call us if you are running later than 15 minutes after your reservation time.</p>
            <p>Always arrive on time.</p>

            <button type="submit" class="btn-primary">Confirm reservation</button>
        </div>
    </form>


    <?php
    include('../../components/footer.php');
    echo '<script src="' . BASE_URL . '/public/scripts/reservations.js"></script>';
    echo "<script src='" . BASE_URL . "/public/scripts/profile.js'></script>";
    ?>
</body>

</html>
