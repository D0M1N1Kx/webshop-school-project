<?php
session_start();
require_once "db_config.php";
require_once "services/UserService.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop</title>
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>
    <nav>
        <?php
        echo '<a href="index.php" class="home">Főoldal</a>';

        if (isset($_SESSION['user_id'])) {
            echo '<span class="welcome-text">Szia, ' . htmlspecialchars($_SESSION['username']) . '!</span>';
            echo '<a href="pages/cart.php">Kosár</a>';

            if ($_SESSION['role'] === 'admin') {
                echo '<a href="pages/admin.php">Admin</a>';
            }

            echo '<a href="pages/logout.php">Kilépés</a>';
        } else {
            echo '<a href="pages/login.php">Bejelentkezés</a>';
            echo '<a href="pages/register.php">Regisztráció</a>';
        }
        ?>
    </nav>

    <div class="content-container">
        <div class="category-bar">
            <div class="category-track">
                <div class="category-item">
                    <img src="images/categories/notebook.webp" alt="Notebook">
                    <span>NOTEBOOK</span>
                </div>
                <div class="category-item">
                    <img src="images/categories/pc.webp" alt="PC">
                    <span>PC</span>
                </div>
                <div class="category-item">
                    <img src="images/categories/motherboard.webp" alt="Alaplap">
                    <span>ALAPLAP</span>
                </div>
                <div class="category-item">
                    <img src="images/categories/processor.webp" alt="Processzor">
                    <span>PROCESSZOR</span>
                </div>
                <div class="category-item">
                    <img src="images/categories/videocard.webp" alt="Videókártya">
                    <span>VIDEÓKÁRTYA</span>
                </div>
                <div class="category-item">
                    <img src="images/categories/ssd.webp" alt="SSD">
                    <span>SSD</span>
                </div>
                <div class="category-item">
                    <img src="images/categories/hdd.webp" alt="HDD">
                    <span>HDD</span>
                </div>
                <div class="category-item">
                    <img src="images/categories/monitor.webp" alt="Monitor">
                    <span>MONITOR</span>
                </div>
                <div class="category-item">
                    <img src="images/categories/tv.webp" alt="TV">
                    <span>TV</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>