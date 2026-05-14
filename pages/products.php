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
</head>

<body>
    <nav>
        <?php
        echo '<a href="../index.php" class="home">Főoldal</a>';

        if (isset($_SESSION['user_id'])) {
            echo '<span class="welcome-text">Szia, ' . htmlspecialchars($_SESSION['username']) . '!</span>';
            echo '<a href="cart.php">Kosár</a>';

            if ($_SESSION['role'] === 'admin') {
                echo '<a href="admin.php">Admin</a>';
            }

            echo '<a href="logout.php">Kilépés</a>';
        } else {
            echo '<a href="login.php">Bejelentkezés</a>';
            echo '<a href="register.php">Regisztráció</a>';
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
                            <p class="product-price"><?= number_format($product['price'], 0, ',', '') ?> Ft</p>
                        </div>
                        <div class="product-actions">
                            <input type="number" value="1" min="1" class="quantity-input">
                            <button class="add-to-cart" onclick="addToCart(<?= $product['id'] ?>)">🛒</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>

</html>