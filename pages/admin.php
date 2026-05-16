<?php
session_start();
require_once "../db_config.php";
require_once "../services/ProductService.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = null;
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
    $category = trim($_POST['category'] ?? '');

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        
        $cleanFileName = time() . "_" . basename($fileName);
        
        $uploadFolder = dirname(__DIR__) . '/images/products/';
        $destPath = $uploadFolder . $cleanFileName;

        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            
            $productService = new ProductService($connection);
            
            $success = $productService->addProduct($name, $description, $price, $stock, $category, $cleanFileName);

            if ($success) {
                $message = "A termék és a kép sikeresen hozzáadva a webshophoz!";
                $messageClass = 'success';
            } else {
                $message = "Hiba történt az adatbázisba mentés során.";
                $messageClass = 'error';
            }
        } else {
            $message = "Hiba történt a kép fájlrendszerbe másolása közben. Nincs jog ide irni: " . htmlspecialchars($destPath);
            $messageClass = 'error';
        }
    } else {
        $message = "Kérlek válassz ki egy érvényes képet! (A fájl mérete lehet, hogy túl nagy)";
        $messageClass = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Termék Kezelés</title>
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="../styles/auth.css"> <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <nav>
        <?php
        echo '<a href="../index.php" class="home"><span class="material-symbols-outlined">home</span></a>';
        echo '<span class="welcome-text">Admin Panel (Szia, ' . htmlspecialchars($_SESSION['username']) . '!)</span>';
        echo '<a href="cart.php"><span class="material-symbols-outlined">shopping_cart</span></a>';
        echo '<a href="admin.php"><span class="material-symbols-outlined">database_search</span></a>';
        echo '<a href="logout.php"><span class="material-symbols-outlined">logout</span></a>';
        ?>
    </nav>

    <div class="content-container">
        <div class="form-container">
            <h2>Új termék hozzáadása</h2>

            <?php if ($message): ?>
                <div class="alert <?= $messageClass ?>"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="input">
                    <label for="name">Termék neve</label>
                    <input type="text" name="name" id="name" required placeholder="pl. ASUS ROG Strix G16">
                </div>

                <div class="input">
                    <label for="category">Kategória</label>
                    <select name="category" id="category" required>
                        <option value="notebook">NOTEBOOK</option>
                        <option value="pc">PC</option>
                        <option value="motherboard">ALAPLAP</option>
                        <option value="processor">PROCESSZOR</option>
                        <option value="videocard">VIDEÓKÁRTYA</option>
                        <option value="ssd">SSD</option>
                        <option value="hdd">HDD</option>
                        <option value="monitor">MONITOR</option>
                        <option value="tv">TV</option>
                    </select>
                </div>

                <div class="input">
                    <label for="price">Ár (Ft)</label>
                    <input type="number" name="price" id="price" min="0" required placeholder="pl. 350000">
                </div>

                <div class="input">
                    <label for="stock">Kezdő készlet (db)</label>
                    <input type="number" name="stock" id="stock" min="0" required placeholder="pl. 5">
                </div>

                <div class="input">
                    <label for="description">Termék leírása</label>
                    <textarea name="description" id="description" placeholder="Specifikációk, részletek..."></textarea>
                </div>

                <div class="input">
                    <label for="image">Termék fotója</label>
                    <input type="file" name="image" id="image" accept="image/*" required>
                </div>

                <button type="submit">Termék mentése</button>
            </form>
        </div>
    </div>
</body>

</html>