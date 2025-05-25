<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $dishId = $_GET['id'] ?? 0;
    
    // 獲取菜式基本信息
    $stmt = $pdo->prepare("SELECT * FROM dishes WHERE id = ?");
    $stmt->execute([$dishId]);
    $dish = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dish) {
        echo json_encode(['success' => false, 'message' => '菜式不存在']);
        exit;
    }

    // 獲取菜式的產品ID
    $stmt = $pdo->prepare("SELECT product_id FROM dish_products WHERE dish_id = ?");
    $stmt->execute([$dishId]);
    $dish['products'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'success' => true,
        'dish' => $dish
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 