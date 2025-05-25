<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    // 獲取菜式基本信息
    $stmt = $pdo->query("SELECT * FROM dishes ORDER BY id DESC");
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 獲取每個菜式的產品
    foreach ($dishes as &$dish) {
        $stmt = $pdo->prepare("
            SELECT p.name, p.price 
            FROM products p 
            JOIN dish_products dp ON p.id = dp.product_id 
            WHERE dp.dish_id = ?
        ");
        $stmt->execute([$dish['id']]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $dish['products'] = array_map(function($p) {
            return $p['name'] . ' ($' . number_format($p['price'], 2) . ')';
        }, $products);
    }

    echo json_encode($dishes);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 