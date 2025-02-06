<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

require_once '../includes/db_connection.php';

// 獲取POST數據
$data = json_decode(file_get_contents('php://input'), true);
$customerId = isset($data['customerId']) ? intval($data['customerId']) : 0;

if ($customerId <= 0) {
    echo json_encode(['success' => false, 'message' => '無效的客戶ID']);
    exit;
}

try {
    // 開始事務
    $pdo->beginTransaction();
    
    // 首先刪除相關的訂單記錄（如果有外鍵關聯）
    $stmt = $pdo->prepare("DELETE FROM orders WHERE customer_id = ?");
    $stmt->execute([$customerId]);
    
    // 然後刪除客戶記錄
    $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->execute([$customerId]);
    
    // 提交事務
    $pdo->commit();
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // 如果出現錯誤，回滾事務
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => '刪除失敗：' . $e->getMessage()]);
}
?> 