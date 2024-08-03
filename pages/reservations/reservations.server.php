<?php
require '../../config.php';
require '../../utils/database.php';

$conn = initialize_database();
function getReservedSlots($conn, $reservation_date, $people_no)
{
    // Use DATE() function to extract the date part from the starts_at column
    $q = "SELECT table_id, starts_at, ends_at 
              FROM table_reservation 
              INNER JOIN `table` ON table_reservation.table_id = `table`.id
              WHERE DATE(starts_at) = '$reservation_date'
              AND `table`.capacity = $people_no
                AND table_reservation.deleted_at IS NULL 
              ORDER BY table_id, starts_at";

    $res = mysqli_query($conn, $q);
    $reserved_slots = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $reserved_slots[] = $row;
    }

    return $reserved_slots;
}

function getAvailableSlots($reserved_slots, $operating_start, $operating_end, $slot_duration)
{
    $available_slots = [];
    $current_time = strtotime($operating_start);
    $end_time = strtotime($operating_end);
    $now = time(); // Get the current time

    while ($current_time < $end_time) {
        // Ensure the slot is in the future
        $slot_end_time = $current_time + ($slot_duration * 60);
        $slot_available = true;

        foreach ($reserved_slots as $slot) {
            $reserved_start = strtotime($slot['starts_at']);
            $reserved_end = strtotime($slot['ends_at']);

            // Check if the current slot overlaps with any reserved slot
            if (!($slot_end_time <= $reserved_start || $current_time >= $reserved_end)) {
                $slot_available = false;
                break;
            }
        }

        // If slot is available, add it to the list
        if ($slot_available) {
            $available_slots[] = date('Y-m-d H:i:s', $current_time);
        }

        // Move to the next slot
        $current_time = $slot_end_time;
    }

    return $available_slots;
}


// handle post requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['reason'] == 'get_available_time_slots') {
        $no_of_people = $_POST['no_of_people'];
        $reservation_date = $_POST['reservation_date'];

        $reserved_slots = getReservedSlots($conn, $reservation_date, $no_of_people);
        $available_slots = getAvailableSlots($reserved_slots, OPERATING_START, OPERATING_END, SLOT_DURATION);
        echo json_encode($available_slots);
    }
}
