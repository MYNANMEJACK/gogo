<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $profession_tag = $_POST['profession_tag'] ?? '';
    $recipe = $_POST['recipe'] ?? '';
    $products = $_POST['products'] ?? [];

    $pdo->beginTransaction();

    // 更新菜式基本信息
    $stmt = $pdo->prepare("UPDATE dishes SET name = ?, type = ?, profession_tag = ?, recipe = ? WHERE id = ?");
    $stmt->execute([$name, $type, $profession_tag, $recipe, $id]);

    // 刪除原有的產品關聯
    $stmt = $pdo->prepare("DELETE FROM dish_products WHERE dish_id = ?");
    $stmt->execute([$id]);

    // 插入新的產品關聯
    $stmt = $pdo->prepare("INSERT INTO dish_products (dish_id, product_id) VALUES (?, ?)");
    foreach ($products as $productId) {
        $stmt->execute([$id, $productId]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 