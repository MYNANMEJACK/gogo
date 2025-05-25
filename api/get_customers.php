<?php
// 設置錯誤處理
ini_set('display_errors', 0);
error_reporting(0);

// 設置響應頭
header('Content-Type: application/json');

// 定義系統常量
define('IN_SYSTEM', true);

try {
    require_once '../includes/db_config.php';
    
    if (!isset($pdo)) {
        throw new Exception("數據庫連接失敗");
    }

    $stmt = $pdo->prepare("SELECT id, name, email, phone, job, gender FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 直接返回用戶數組，不包裝在 data 字段中
    echo json_encode($users, JSON_UNESCAPED_UNICODE);
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?> 