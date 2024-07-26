<?php
function renderMenuItem(string $title, string $price, string $image, bool $showAddToCartButton = false)
{
    $addToCartButton = $showAddToCartButton ? '<button class="btn-secondary">Add to cart</button>' : '';

    echo <<< HTML
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
}
