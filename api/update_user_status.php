<?php
session_start();
require_once '../includes/db_config.php';

// 嚴格檢查：必須是超級管理員
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    echo json_encode(['success' => false, 'message' => '權限不足']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'] ?? 0;
    $status = $data['status'] ?? '';

    $stmt = $pdo->prepare("UPDATE admins SET status = ? WHERE id = ?");
    $stmt->execute([$status, $userId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 