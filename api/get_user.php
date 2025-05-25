<?php
// 開啟錯誤報告
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

header('Content-Type: application/json');

try {
    // 檢查是否有用戶ID
    if (!isset($_GET['id'])) {
        throw new Exception('缺少用戶ID');
    }

    $userId = intval($_GET['id']);
    
    // 添加調試信息
    error_log("Executing query for user ID: " . $userId);
    
    $stmt = $pdo->prepare("
        SELECT id, username, email, full_name, role, status 
        FROM admins 
        WHERE id = ?
    ");
    
    // 執行查詢
    $stmt->execute([$userId]);
    
    // 獲取用戶數據
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 添加調試信息
    error_log("Query result: " . print_r($user, true));
    
    if (!$user) {
        throw new Exception('找不到該用戶');
    }

    $response = [
        'success' => true,
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'full_name' => $user['full_name'],
        'role' => $user['role'],
        'status' => $user['status']
    ];
    
    echo json_encode($response);
    error_log("Response: " . json_encode($response));
    
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