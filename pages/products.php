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
        <div class="products-container">Termekek</div>
    </div>

</body>

</html>