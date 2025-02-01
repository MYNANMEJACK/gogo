<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    echo json_encode(['success' => false, 'message' => '權限不足']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'] ?? 0;

    // 防止刪除自己的賬號
    if ($userId == $_SESSION['admin_id']) {
        echo json_encode(['success' => false, 'message' => '不能刪除自己的賬號']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->execute([$userId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 