<?php
session_start();
require_once "../db_config.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

$query = "
    SELECT ci.id AS cart_item_id, p.name, p.price, p.image, ci.quantity
    FROM cart_items ci
    JOIN carts c ON ci.cart_id = c.id
    JOIN products p ON ci.product_id = p.id
    WHERE c.user_id = ?
";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$grandTotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kosár</title>
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/cart.css">
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
        <div class="cart-wrapper">
            <h2>Kosarad elemei</h2>

            <?php if (empty($cartItems)): ?>
                <p id="empty-message">A kosarad jelenleg teljesen üres.</p>
            <?php else: ?>
                <div id="cart-list">
                    <?php foreach ($cartItems as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $grandTotal += $subtotal;
                    ?>
                        <div class="cart-item" id="item-<?= $item['cart_item_id'] ?>">
                            <img src="../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            
                            <div class="item-info">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p><?= number_format($item['price'], 0, ',', ' ') ?> Ft / db</p>
                            </div>

                            <div class="qty-controls">
                                <button class="qty-btn" onclick="modifyQty(<?= $item['cart_item_id'] ?>, -1)">-</button>
                                <span class="qty-text"><?= $item['quantity'] ?></span>
                                <button class="qty-btn" onclick="modifyQty(<?= $item['cart_item_id'] ?>, 1)">+</button>
                            </div>

                            <button class="remove-btn" onclick="removeItem(<?= $item['cart_item_id'] ?>)">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-total">
                    Összesen: <span id="grand-total"><?= number_format($grandTotal, 0, ',', ' ') ?></span> Ft
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function modifyQty(itemId, change) {
        fetch('update_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=update&item_id=${itemId}&change=${change}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.new_quantity <= 0) {
                    document.getElementById(`item-${itemId}`).remove();
                } else {
                    document.querySelector(`#item-${itemId} .qty-text`).textContent = data.new_quantity;
                }
                
                document.getElementById('grand-total').textContent = data.new_total;
                checkIfCartIsEmpty();
            }
        });
    }

    function removeItem(itemId) {
        if (confirm("Biztosan ki szeretnéd venni ezt a terméket a kosárból?")) {
            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete&item_id=${itemId}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`item-${itemId}`).remove();
                    document.getElementById('grand-total').textContent = data.new_total;
                    checkIfCartIsEmpty();
                }
            });
        }
    }

    function checkIfCartIsEmpty() {
        const list = document.getElementById('cart-list');
        if (list && list.children.length === 0) {
            location.reload();
        }
    }
    </script>
</body>
</html>