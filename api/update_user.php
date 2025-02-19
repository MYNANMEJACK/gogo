<?php
// 開啟錯誤報告
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

header('Content-Type: application/json');

try {
    // 檢查是否為POST請求
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('無效的請求方法');
    }

    // 獲取表單數據
    $userId = $_POST['userId'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $fullName = $_POST['full_name'] ?? '';
    $role = $_POST['role'] ?? '';

    // 驗證角色值
    if (!in_array($role, ['admin', 'super_admin'])) {
        throw new Exception('無效的角色值');
    }

    // 驗證必填字段
    if (!$userId || !$username || !$email || !$fullName || !$role) {
        throw new Exception('所有字段都是必填的');
    }

    // 調試信息
    error_log("Updating user: " . print_r($_POST, true));

    // 檢查用戶名是否已存在（排除當前用戶）
    $stmt = $pdo->prepare("
        SELECT id FROM admins 
        WHERE username = ? AND id != ?
    ");
    $stmt->execute([$username, $userId]);
    if ($stmt->fetch()) {
        throw new Exception('用戶名已存在');
    }
    
    // 更新用戶信息
    $stmt = $pdo->prepare("
        UPDATE admins 
        SET username = ?, 
            email = ?, 
            full_name = ?, 
            role = ?
        WHERE id = ?
    ");
    
    $result = $stmt->execute([
        $username,
        $email,
        $fullName,
        $role,
        $userId
    ]);
    
    if (!$result) {
        throw new Exception('更新失敗');
    }

    // 調試信息
    error_log("Update successful for user ID: $userId");

    // 返回成功響應
    echo json_encode([
        'success' => true, 
        'message' => '用戶更新成功',
        'debug' => [
            'userId' => $userId,
            'username' => $username,
            'email' => $email,
            'fullName' => $fullName,
            'role' => $role
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => '數據庫錯誤: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Application error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?> 