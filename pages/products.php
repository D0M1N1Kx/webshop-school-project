<?php
session_start();
require_once "../db_config.php";
require_once "../services/ProductService.php";

$productService = new ProductService($connection);

$category = $_GET['category'] ?? null;

if ($category) {
    $products = $productService->getByCategory($category);
} else {
    $products = $productService->getAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop</title>
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/products.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <nav>
        <?php
        echo '<a href="../index.php" class="home"><span class="material-symbols-outlined">home</span></a>';

        if (isset($_SESSION['user_id'])) {
            echo '<span class="welcome-text">Szia, ' . htmlspecialchars($_SESSION['username']) . '!</span>';
            echo '<a href="cart.php"><span class="material-symbols-outlined">shopping_cart</span></a>';

            if ($_SESSION['role'] === 'admin') {
                echo '<a href="admin.php"><span class="material-symbols-outlined">database_search</span></a>';
            }

            echo '<a href="logout.php"><span class="material-symbols-outlined">logout</span></a>';
        } else {
            echo '<a href="login.php"><span class="material-symbols-outlined">login</span></a>';
            echo '<a href="register.php"><span class="material-symbols-outlined">account_box</span></a>';
        }
        ?>
    </nav>
    <div class="content-container">
        <div class="filter-container">
            <div class="filter-header">
                <p>Szűrés</p>
                <span>Mindet törlöm</span>
            </div>
            <div class="filter-price">
                <div class="filter-section-header">
                    <span id="sub-header">Ár</span>
                </div>
                <div class="price-inputs">
                    <input type="number" id="min-price-input" value="0"> <span>Ft</span>
                    <span>-</span>
                    <input type="number" id="max-price-input" value="1000000"> <span>Ft</span>
                </div>
            </div>
        </div>
        <div class="products-container">
            <div class="products-header">
                <div class="products-title">
                    <h2><?= htmlspecialchars($category ?? 'Összes termék') ?></h2>
                    <span class="product-count"><?= count($products) ?> termék</span>
                </div>
                <div class="products-search">
                    <input type="text" id="search-input" placeholder="Keresés...">
                </div>
            </div>

            <div class="products-grid" id="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="../images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="product-info">
                            <p class="product-name"><?= htmlspecialchars($product['name']) ?></p>
                            <p class="product-price"><?= number_format($product['price'], 0, ',', ' ') ?> Ft</p>
                        </div>
                        <div class="product-actions">
                            <input type="number" value="1" min="1" class="quantity-input">
                            <button class="add-to-cart" onclick="addToCart(<?= $product['id'] ?>)"><span class="material-symbols-outlined">shopping_cart</span></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            const minPriceInput = document.getElementById('min-price-input');
            const maxPriceInput = document.getElementById('max-price-input');

            const productCards = document.querySelectorAll('.product-card');
            const countSpan = document.querySelector('.product-count');

            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase().trim();

                const minPrice = parseFloat(minPriceInput.value) || 0;
                const maxPrice = parseFloat(maxPriceInput.value) || Infinity;

                let visibleCount = 0;

                productCards.forEach(card => {
                    const productName = card.querySelector('.product-name').textContent.toLowerCase();

                    const priceText = card.querySelector('.product-price').textContent;
                    const productPrice = parseFloat(priceText.replace(/[^0-9]/g, ''));

                    const matchesSearch = productName.includes(searchTerm);
                    const matchesMinPrice = productPrice >= minPrice;
                    const matchesMaxPrice = productPrice <= maxPrice;

                    if (matchesSearch && matchesMinPrice && matchesMaxPrice) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (countSpan) {
                    countSpan.textContent = visibleCount + ' termék';
                }
            }

            searchInput.addEventListener('input', filterProducts);
            minPriceInput.addEventListener('input', filterProducts);
            maxPriceInput.addEventListener('input', filterProducts);
        });
    </script>

</body>

</html>