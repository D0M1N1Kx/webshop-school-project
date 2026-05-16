<?php
session_start();
require_once "../db_config.php";
require_once "../services/ProductService.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Nincs jogosultságod!");
}

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
        
        $uploadFolder = '../images/products/';
        $destPath = $uploadFolder . $cleanFileName;

        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            
            $productService = new ProductService($connection);
            
            $success = $productService->addProduct($name, $description, $price, $stock, $category, $cleanFileName);

            if ($success) {
                echo "Termék sikeresen hozzáadva és a kép is lementve!";
            } else {
                echo "A kép felkerült, de az adatbázis mentés hibás volt.";
            }

        } else {
            echo "Hiba történt a kép elmentése során a szerver mappájába.";
        }
    } else {
        echo "Nem választottál ki képet, vagy a kép túl nagy.";
    }
}