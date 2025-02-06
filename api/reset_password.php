<?php
require_once '../includes/db_connection.php';

// 獲取POST數據
$data = json_decode(file_get_contents('php://input'), true);
$customerId = isset($data['customerId']) ? intval($data['customerId']) : 0;

if ($customerId <= 0) {
    echo json_encode(['success' => false, 'message' => '無效的客戶ID']);
    exit;
}

try {
    // 生成新的隨機密碼（8位字符）
    $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
    
    // 對新密碼進行加密
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // 更新數據庫中的密碼
    $stmt = $pdo->prepare("UPDATE customers SET password = ? WHERE id = ?");
    $result = $stmt->execute([$hashedPassword, $customerId]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => "密碼已重置為：" . $newPassword
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => '重置密碼失敗']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤：' . $e->getMessage()]);
}
?> 