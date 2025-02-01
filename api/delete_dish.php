<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $dishId = $data['dishId'] ?? 0;

    $pdo->beginTransaction();

    // 刪除產品關聯
    $stmt = $pdo->prepare("DELETE FROM dish_products WHERE dish_id = ?");
    $stmt->execute([$dishId]);

    // 刪除菜式
    $stmt = $pdo->prepare("DELETE FROM dishes WHERE id = ?");
    $stmt->execute([$dishId]);

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 