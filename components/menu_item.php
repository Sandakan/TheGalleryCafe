<?php
function renderMenuItem(int $id, string $title, string $price, string $image, bool $showAddToCartButton = true)
{
    $addToCartButtonLink = BASE_URL . '/pages/menu/menu-item.php?product-id=' . $id;
    $addToCartButton = $showAddToCartButton ? '<a href="' . $addToCartButtonLink . '" class="btn-secondary">More Info</a>' : '';
    $menu_item = <<< HTML
    <div class="menu-item">
        <div class="menu-item-image">
            <img src="$image" alt="Black Cookie Latte">
        </div>
        <div class="menu-item-info">
            <h3 class="menu-item-title">$title</h3>
            <h4 class="menu-item-price">LKR $price</h4>
        </div>
        
      $addToCartButton
    </div>
    HTML;

    return $menu_item;
}
