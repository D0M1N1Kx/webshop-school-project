<?php
require_once "db_config.php";
require_once "services/UserService.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop</title>
</head>

<body>
    <nav>
        <?php
        echo '<a href="index.php">Főoldal</a>';

        if (isset($_SESSION['user_id'])) {
            echo '<span>Szia, ' . htmlspecialchars($_SESSION['username']) . '!</span>';
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
</body>

</html>