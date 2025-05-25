<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $profession_tag = $_POST['profession_tag'] ?? '';
    $recipe = $_POST['recipe'] ?? '';
    $products = $_POST['products'] ?? [];

    $pdo->beginTransaction();

    // 插入菜式基本信息
    $stmt = $pdo->prepare("INSERT INTO dishes (name, type, profession_tag, recipe) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $type, $profession_tag, $recipe]);
    $dishId = $pdo->lastInsertId();

    // 插入菜式產品關聯
    $stmt = $pdo->prepare("INSERT INTO dish_products (dish_id, product_id) VALUES (?, ?)");
    foreach ($products as $productId) {
        $stmt->execute([$dishId, $productId]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 