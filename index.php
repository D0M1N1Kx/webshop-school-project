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
        <div class="category-bar">
            <div class="category-track">
                <a href="pages/products.php?category=notebook" class="category-item">
                    <img src="images/categories/notebook.webp" alt="Notebook">
                    <span>NOTEBOOK</span>
                </a>
                <a href="pages/products.php?category=pc" class="category-item">
                    <img src="images/categories/pc.webp" alt="PC">
                    <span>PC</span>
                </a>
                <a href="pages/products.php?category=motherboard" class="category-item">
                    <img src="images/categories/motherboard.webp" alt="Alaplap">
                    <span>ALAPLAP</span>
                </a>
                <a href="pages/products.php?category=processor" class="category-item">
                    <img src="images/categories/processor.webp" alt="Processzor">
                    <span>PROCESSZOR</span>
                </a>
                <a href="pages/products.php?category=videocard" class="category-item">
                    <img src="images/categories/videocard.webp" alt="Videókártya">
                    <span>VIDEÓKÁRTYA</span>
                </a>
                <a href="pages/products.php?category=ssd" class="category-item">
                    <img src="images/categories/ssd.webp" alt="SSD">
                    <span>SSD</span>
                </a>
                <a href="pages/products.php?category=hdd" class="category-item">
                    <img src="images/categories/hdd.webp" alt="HDD">
                    <span>HDD</span>
                </a>
                <a href="pages/products.php?category=monitor" class="category-item">
                    <img src="images/categories/monitor.webp" alt="Monitor">
                    <span>MONITOR</span>
                </a>
                <a href="pages/products.php?category=tv" class="category-item">
                    <img src="images/categories/tv.webp" alt="TV">
                    <span>TV</span>
                </a>
            </div>
        </div>
    </div>
</body>

</html>