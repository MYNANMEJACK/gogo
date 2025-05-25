<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $imageId = $data['imageId'] ?? 0;

    // 檢查圖片是否被使用
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE image_url = (SELECT url FROM images WHERE id = ?)");
    $stmt->execute([$imageId]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => '該圖片正在被使用，無法刪除']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
    $stmt->execute([$imageId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 