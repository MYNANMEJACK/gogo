<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $orderId = $_GET['id'] ?? 0;
    
    // 獲取訂單基本信息
    $stmt = $pdo->prepare("
        SELECT 
            o.*,
            u.name as user_name,
            u.phone
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.id = ?
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        echo json_encode(['success' => false, 'message' => '訂單不存在']);
        exit;
    }
    
    // 獲取訂單項目
    $stmt = $pdo->prepare("
        SELECT 
            product_name,
            quantity,
            price
        FROM order_items 
        WHERE order_id = ?
    ");
    $stmt->execute([$orderId]);
    $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 格式化數據
    $order['total_price'] = number_format((float)$order['total_price'], 2, '.', '');
    $order['created_at'] = date('Y-m-d H:i:s', strtotime($order['created_at']));
    
    echo json_encode([
        'success' => true,
        'data' => $order
    ]);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => '系統錯誤',
        'error' => $e->getMessage()
    ]);
} 