<?php
require '../../../../config.php';
require '../../../../utils/database.php';
require '../../../../utils/authenticate.php';

$conn = initialize_database();
session_start();

authenticate(array('ADMIN'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['reason'] == 'delete_event' && isset($_POST['event_id'])) {
        $q = <<<SQL
            UPDATE `event` SET `deleted_at` = NOW() WHERE `id` = {$_POST['event_id']};
        SQL;
        $res = mysqli_query($conn, $q);

        if (!$res) {
            echo mysqli_error($conn);
        }
    }
}

$query = <<<SQL
    SELECT * FROM `event` WHERE `deleted_at` IS NULL;
SQL;

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Events - The Gallery Café</title>
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
                <div class="dashboard-content admin-dashboard-events">
                    <header>
                        <h2>Events</h2>
                        <div class="header-actions-container">
                            <a href="<?php echo BASE_URL; ?>/pages/dashboard/admin/events/add_event.php" class="btn-secondary"><span class="material-symbols-rounded btn-icon">add</span><span>Add Event</span></a>
                        </div>
                    </header>

                    <table class="events-container">
                        <thead>
                            <tr>
                                <th class="event-id">Event ID</th>
                                <th class="event-name">Name</th>
                                <th class="event-description">Description</th>
                                <th class="event-starts-at">Starts At</th>
                                <th class="event-ends-at">Ends At</th>
                                <th class="event-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo <<<HTML
                                    <tr>
                                        <td class="event-id">#{$row['id']}</td>
                                        <td class="event-name">{$row['name']}</td>
                                        <td class="event-description">{$row['description']}</td>
                                        <td class="event-starts-at">{$row['starts_at']}</td>
                                        <td class="event-ends-at">{$row['ends_at']}</td>
                                        <td class="event-actions">
                                           <div class="actions-container">
                                               <a href="{$BASE_URL}/pages/dashboard/admin/events/edit_event.php?event_id={$row['id']}" class="btn-secondary btn-only-icon" title="Edit Event">
                                                   <span class="material-symbols-rounded btn-icon">edit</span>
                                                </a>
                                                <button class="btn-secondary btn-only-icon" title="Delete Event" onclick="deleteEvent({$row['id']})">
                                                    <span class="material-symbols-rounded btn-icon">delete</span>
                                                </button>
                                           </div>
                                        </td>
                                    </tr>
                                HTML;
                                }
                            } else {
                                echo '<tr><td colspan="6" class="no-results">No events found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php
    include('../../../../components/footer.php');
    mysqli_close($conn);
    echo "<script src='" . BASE_URL . "/public/scripts/view_events.js'></script>";
    ?>
</body>

</html>
