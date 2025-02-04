<?php
// 開啟錯誤報告
ini_set('display_errors', 0); // 關閉HTML錯誤輸出
error_reporting(E_ALL);

// 啟動會話
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 檢查用戶是否已登錄
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => '未登錄或會話已過期'
    ]);
    exit;
}

// 檢查用戶是否有權限（可選）
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'super_admin') {
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => '沒有權限執行此操作'
    ]);
    exit;
}
?> 