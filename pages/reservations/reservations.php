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
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.png" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <div class="model-container reservation-model-container">
        <div class="reservation-info-container">
            <h1 class="create-reservation-heading">Create A Reservation</h1>
            <p>
                Creating a reservation at The Gallery Café ensures that you have a guaranteed spot in our popular restaurant, eliminating the wait time and enhancing your dining experience. Reservations help us prepare for your visit, ensuring that our team can provide you with the best service and culinary delights that our café is known for.
            </p>
        </div>

        <div class="model reservation-model find-table-model">
            <h2 class="create-reservation-heading">1. Find a table</h2>
            <p>Select a table that suits your needs. Please note that a selected table will be reserved for you upto 5 minutes during the reservation until you complete the reservation.</p>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="input-group-container">
                    <div class="input-container">
                        <label for="no_of_people">Number of people *</label>
                        <select type="text" name="no_of_people" id="no_of_people" placeholder="Select the no of people" required>
                            <option value="" selected disabled>Select the number of people</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>

                    <div class="input-container">
                        <label for="reservation_date">Reservation Date *</label>
                        <input type="date" name="reservation_date" id="reservation_date" placeholder="<?php echo date("Y-m-d") ?>" required />
                    </div>

                    <div class="input-container">
                        <label for="reservation_date">Reservation Date *</label>
                        <input type="time" name="reservation_time" id="reservation_time" required />
                    </div>

                </div>
                <button type="submit" class="btn-primary">Find Table</button>
            </form>
        </div>

        <div class="model reservation-model enter-details-model">
            <h2 class="create-reservation-heading">2. Enter your details</h2>
            <p>Enter your details below about the reservation you wish to make.</p>

            <div class="logged-in-account">
                <b>Using logged in account</b>

                <div class="account">
                    <div class="account-info"><span class="material-symbols-rounded-filled">account_circle</span> <?php echo $_SESSION['user_first_name']; ?></div>
                    <button type="submit" class="btn-secondary">Enter details manually</button>

                </div>
            </div>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="input-group-container">
                    <div class="input-container">
                        <label for="occassion">Select an occassion (optional)</label>
                        <select type="text" name="occassion" id="occassion" placeholder="Select an occasion">
                            <option value="" selected disabled>Select an occassion (optional)</option>
                            <option value="Birthday">Birthday</option>
                            <option value="Anniversary">Anniversary</option>
                            <option value="Meeting">Meeting</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="input-container">
                        <label for="special_request">Add a special request (optional)</label>
                        <input type="text" name="special_request" id="special_request" placeholder="Play a song by Taylor Swift" />
                    </div>
                </div>
                <button type="submit" class="btn-primary">Confirm Details</button>
            </form>
        </div>

        <div class="model reservation-model pre-order-items-model">
            <h2 class="create-reservation-heading">3. Pre-order menu items for reservation</h2>
            <p>Please select the menu items you wish to pre-order for the reservation. Pre-ordered menu items will be delivered to you at the start of the reservation with minimal delay.</p>

            <div class="options-container">
                <label class="option" for="pre-orders-enabled">
                    <h3>Pre-order cart items for reservation</h3>
                    <input type="radio" name="pre_orders" id="pre-orders-enabled" value="enabled" />
                </label>

                <label class="option" for="pre-orders-disabled">
                    <h3>Order items at the reservation</h3>
                    <input type="radio" name="pre_orders" id="pre-orders-disabled" value="disabled" />
                </label>
            </div>

            <button type="submit" class="btn-primary">Place pre-orders</button>

        </div>

        <div class="model reservation-model confirm-model">
            <h2 class="create-reservation-heading">4. Confirm reservation</h2>
            <p>Please make sure that all the information provided are correct to provide you the best experience and to provide you with an exceptional service.</p>
            <p>We may contact you about this reservation, so keep the email and the contact number you provided free and available.</p>
            <p>Always arrive on time.</p>

            <button type="submit" class="btn-primary">Confirm reservation</button>
        </div>

    </div>

    <?php include('../../components/footer.php'); ?>
</body>

</html>
