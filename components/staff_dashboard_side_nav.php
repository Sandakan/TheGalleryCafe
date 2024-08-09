<!-- isActivePage, BASE_URL and getFullUrl are defined in utils\header_navigation_bar.php -->

<?php
$links = array(
    array('name' => 'Statistics', 'icon' => 'monitoring', 'url' => '/pages/dashboard/staff/staff_dashboard.php'),
    array('name' => 'Reservations', 'icon' => 'event_seat', 'url' => '/pages/dashboard/staff/reservations/view_reservations.php'),
    array('name' => 'Orders', 'icon' => 'orders', 'url' => '/pages/dashboard/staff/orders/view_orders.php'),
)

?>

<nav class="dashboard-nav">
    <?php
    foreach ($links as $link) {
        $isActive = isActivePage($link['url']);
        $href = BASE_URL . $link['url'];
        $icon = $link['icon'];
        $name = $link['name'];

        echo <<< HTML
        <a class="dashboard-nav-link $isActive" href="$href">
            <span class="link-icon material-symbols-rounded">$icon</span>
            <span class="link-text">$name</span>
        </a>
        HTML;
    }

    ?>
</nav>
