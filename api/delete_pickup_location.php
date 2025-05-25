<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $locationId = $data['locationId'] ?? 0;

    // 檢查是否有相關訂單
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE pickup_location = (SELECT address FROM pickup_locations WHERE id = ?)");
    $stmt->execute([$locationId]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => '該自提點有相關訂單，無法刪除']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM pickup_locations WHERE id = ?");
    $stmt->execute([$locationId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 