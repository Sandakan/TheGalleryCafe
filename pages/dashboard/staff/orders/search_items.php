<?php
require '../../../../config.php';
require '../../../../utils/database.php';

$conn = initialize_database();

function getSearchResults($conn, $search)
{
    $sql = "SELECT * FROM `menu_item` WHERE `name` LIKE '%$search%' AND `deleted_at` IS NULL LIMIT 3;";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return array();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search_text']) && !empty($_POST['search_text'])) {
        $search = $_POST['search_text'];
        echo json_encode(getSearchResults($conn, $search));
    }
}
