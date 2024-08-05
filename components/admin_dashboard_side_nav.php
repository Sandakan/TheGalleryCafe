<!-- isActivePage, BASE_URL and getFullUrl are defined in utils\header_navigation_bar.php -->

<?php
$links = array(
    array('name' => 'Statistics', 'icon' => 'monitoring', 'url' => '/pages/dashboard/admin/admin_dashboard.php'),
    array('name' => 'Users', 'icon' => 'monitoring', 'url' => '/pages/dashboard/admin/users/view_users.php'),
    array('name' => 'Menus', 'icon' => 'restaurant_menu', 'url' => '/pages/dashboard/admin/menus/view_menus.php'),
    array('name' => 'Reservations', 'icon' => 'event_seat', 'url' => '/pages/dashboard/admin/reservations/view_reservations.php'),
    array('name' => 'Orders', 'icon' => 'orders', 'url' => '/pages/dashboard/admin/orders/view_orders.php'),
    array('name' => 'Special Events', 'icon' => 'event', 'url' => '/pages/dashboard/admin/events/view_events.php'),
    array('name' => 'Promotions', 'icon' => 'editor_choice', 'url' => '/pages/dashboard/admin/promotions/view_promotions.php'),
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
