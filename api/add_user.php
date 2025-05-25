<?php
session_start();
require_once '../includes/db_config.php';

// 嚴格檢查：必須是超級管理員
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    echo json_encode(['success' => false, 'message' => '權限不足']);
    exit;
}

try {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $role = $_POST['role'] ?? 'admin';

    // 檢查用戶名是否已存在
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => '用戶名已存在']);
        exit;
    }

    // 創建新用戶
    $stmt = $pdo->prepare("INSERT INTO admins (username, password, email, full_name, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $username,
        password_hash($password, PASSWORD_DEFAULT),
        $email,
        $full_name,
        $role
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 