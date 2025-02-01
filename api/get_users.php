<?php
session_start();
require_once '../includes/db_config.php';

// 嚴格檢查：必須是超級管理員
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    echo json_encode(['success' => false, 'message' => '權限不足']);
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, username, email, full_name, role, status, last_login FROM admins ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 