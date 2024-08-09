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
    $menu_items = array();

    if (mysqli_num_rows($result) > 0) {
        // Loop through each result and display in table rows
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($menu_items, renderMenuItem(intval($row['id']), $row['name'], $row['price'], BASE_URL . '/public/images/menu-items/' . $row['image']));
        }
    }

    return implode("", $menu_items);
}

$is_search_enabled = false;
$q = "";
$filter = "All";
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = $_GET['q'];
    $is_search_enabled = true;
}

if (isset($_GET['filter']) && !empty($_GET['filter'])) {
    $filter = $_GET['filter'];
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
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/images/logo.webp" type="image/x-icon">
</head>

<body>
    <?php include('../../components/header_navigation_bar.php'); ?>

    <section class="menus-container">
        <div class="menu-info">
            <h3>Our Menu</h3>
            <p>Whether you're in the mood for a hearty breakfast, a leisurely lunch, or an exquisite dinner, our featured menus offer something to satisfy every palate. Join us and savor the exceptional cuisine that defines The Gallery Café experience.</p>
            <form class="menu-search-form" method="GET" action="menu.php">
                <div class="search-input-container">
                    <input type="search" id="items-search" name="q" placeholder="Search menu items here" value="<?= $q ?>" />
                    <button class="btn-secondary" id="search-items-btn" type="submit">Search</button>
                </div>

                <p class="filter-description">&bull; &bull; <span>filter by cuisine</span> &bull; &bull;</p>
                <div class="menu-filters">
                    <?php
                    $selected = (empty($filter) || $filter == "All") ? "checked" : "";
                    echo <<< HTML
                        <label for="cuisine-0" class="filter">
                            <input type="radio" id="cuisine-0" name="filter" value="All" $selected />
                            <span>
                                <span class="material-symbols-rounded checked">radio_button_checked</span>
                                <span class="material-symbols-rounded unchecked">radio_button_unchecked</span>
                                All
                            </span>
                        </label>
                    HTML;

                    $sql = "SELECT id, name FROM cuisine";

                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = ($filter == $row['name']) ? "checked" : "";
                            echo <<< HTML
                                <label for="cuisine-$row[id]" class="filter">
                                    <input type="radio" id="cuisine-$row[id]" name="filter" value="$row[name]" $selected/>
                                    <span>
                                        <span class="material-symbols-rounded checked">radio_button_checked</span>
                                        <span class="material-symbols-rounded unchecked">radio_button_unchecked</span>
                                         $row[name]
                                    </span>
                                </label>
                            HTML;
                        }
                    }

                    ?>
                </div>
            </form>
        </div>

        <?php
        if ($is_search_enabled) {
            $sql = <<< SQL
            SELECT
                menu_item.id,
                menu_item.`name`,
                price,
                image,
                cuisine.`name` AS cuisine_type 
            FROM
                menu_item
                INNER JOIN cuisine ON cuisine.id = menu_item.cuisine_id 
            WHERE
                menu_item.`name` LIKE '%$q%' 
                AND ( CASE WHEN '$filter' = 'All' THEN TRUE ELSE cuisine_id = ( SELECT id FROM cuisine WHERE NAME = '$filter' ) END );
            
            SQL;
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $menu_items = '';

                while ($row = mysqli_fetch_assoc($result)) {
                    $menu_items .= renderMenuItem(intval($row['id']), $row['name'], $row['price'], BASE_URL . '/public/images/menu-items/' . $row['image']);
                }

                echo <<< HTML
                    <div class="menu-category">
                        <div class="menu-category-info">
                            <h4 class="menu-category-title">Search results for '$q'</h4>
                        </div>
                        <div class="menus">$menu_items</div>
                    </div>
                    HTML;
            } else {
                echo <<< HTML
                    <div class="menu-category">
                        <div class="menu-category-info">
                            <h4 class="menu-category-title">Search results for '$q'</h4>
                        </div>
                        <div class="menu-no-results">
                            <span class="material-symbols-rounded">release_alert</span>
                            <p>No results found for '$q'.</p>
                            <p>Try again with different keywords and filters.</p>
                        </div>
                    </div>
                HTML;
            }
        } else {
            $sql = "SELECT id, name, description FROM menu WHERE deleted_at IS NULL";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $menu_name = $row['name'];
                    $menu_description = $row['description'];
                    $menu_items = displayMenuItems(intval($row['id']), $conn);

                    echo <<< HTML
                    <div class="menu-category">
                        <div class="menu-category-info">
                            <h4 class="menu-category-title">$menu_name</h4>
                            <p class="menu-category-description">$menu_description</p>
                        </div>
                        <div class="menus">$menu_items</div>
                    </div>
                    HTML;
                }
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
