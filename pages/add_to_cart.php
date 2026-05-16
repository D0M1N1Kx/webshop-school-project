<?php
session_start();
require_once "../db_config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Kérlek, jelentkezz be a vásárláshoz!']);
    exit;
}

$userId = $_SESSION['user_id'];
$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) ?? 1;

if (!$productId || $quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Hibás termék vagy darabszám.']);
    exit;
}

$stmt = $connection->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$cart = $stmt->get_result()->fetch_assoc();

if (!$cart) {
    echo json_encode(['success' => false, 'message' => 'Nem található kosár ehhez a felhasználóhoz.']);
    exit;
}

$cartId = $cart['id'];

$stmt = $connection->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
$stmt->bind_param("ii", $cartId, $productId);
$stmt->execute();
$existingItem = $stmt->get_result()->fetch_assoc();

if ($existingItem) {
    $newQuantity = $existingItem['quantity'] + $quantity;
    $updateStmt = $connection->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $updateStmt->bind_param("ii", $newQuantity, $existingItem['id']);
    $updateStmt->execute();
} else {
    $insertStmt = $connection->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
    $insertStmt->bind_param("iii", $cartId, $productId, $quantity);
    $insertStmt->execute();
}

echo json_encode(['success' => true]);