<?php
// function renderMenuItem($title, $price, $image, $showAddToCartButton = true)
// {
//     $addToCartButtonLink = BASE_URL . '/pages/menu/menu-item.php?product-id=1';
//     $addToCartButton = $showAddToCartButton ? '<a href="'. $addToCartButtonLink .'" class="btn-secondary">More Info</a>' : '';

//     // Use traditional string concatenation for PHP 5.5 compatibility
//     echo '<div class="menu-item">';
//     echo '    <div class="menu-item-image">';
//     echo '        <img src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '">';
//     echo '    </div>';
//     echo '    <div class="menu-item-info">';
//     echo '        <h3 class="menu-item-title">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h3>';
//     echo '        <h4 class="menu-item-price">LKR ' . htmlspecialchars($price, ENT_QUOTES, 'UTF-8') . '</h4>';
//     echo '    </div>';
//     echo '    ' . $addToCartButton;  
//     echo '</div>';
// }

function renderMenuItem(int $id, string $title, string $price, string $image, bool $showAddToCartButton = true)
{
    $addToCartButtonLink = BASE_URL . '/pages/menu/menu-item.php?product-id=' . $id;
    $addToCartButton = $showAddToCartButton ? '<a href="' . $addToCartButtonLink . '" class="btn-secondary">More Info</a>' : '';
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
