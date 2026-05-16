<?php
session_start();
require_once "../db_config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);

if (!$itemId) {
    echo json_encode(['success' => false]);
    exit;
}

if ($action === 'update') {
    $change = filter_input(INPUT_POST, 'change', FILTER_VALIDATE_INT);
    
    $stmt = $connection->prepare("SELECT quantity FROM cart_items WHERE id = ?");
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    
    $newQty = $item['quantity'] + $change;
    
    if ($newQty <= 0) {
        $delStmt = $connection->prepare("DELETE FROM cart_items WHERE id = ?");
        $delStmt->bind_param("i", $itemId);
        $delStmt->execute();
    } else {
        $updStmt = $connection->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $updStmt->bind_param("ii", $newQty, $itemId);
        $updStmt->execute();
    }
} elseif ($action === 'delete') {
    $delStmt = $connection->prepare("DELETE FROM cart_items WHERE id = ?");
    $delStmt->bind_param("i", $itemId);
    $delStmt->execute();
    $newQty = 0;
}

$query = "
    SELECT SUM(p.price * ci.quantity) AS total 
    FROM cart_items ci
    JOIN carts c ON ci.cart_id = c.id
    JOIN products p ON ci.product_id = p.id
    WHERE c.user_id = ?
";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$totalResult = $stmt->get_result()->fetch_assoc();
$newTotal = $totalResult['total'] ?? 0;

echo json_encode([
    'success' => true,
    'new_quantity' => $newQty,
    'new_total' => number_format($newTotal, 0, ',', ' ')
]);