<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    // 檢查數據庫連接
    if (!$pdo) {
        throw new Exception('數據庫連接失敗');
    }

    $status = $_GET['status'] ?? '';
    
    // 基本查詢，添加 users 表關聯
    $query = "
        SELECT 
            o.id,
            o.user_id,
            u.name as user_name,
            o.delivery_method,
            o.payment_method,
            o.total_price,
            o.status,
            o.created_at,
            o.address,
            o.pickup_location,
            GROUP_CONCAT(oi.product_name) as products
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
    ";
    
    // 添加狀態過濾
    if ($status) {
        $query .= " WHERE o.status = ?";
    }
    
    // 分組和排序
    $query .= " GROUP BY o.id ORDER BY o.created_at DESC";
    
    // 準備並執行查詢
    $stmt = $pdo->prepare($query);
    if ($status) {
        $stmt->execute([$status]);
    } else {
        $stmt->execute();
    }
    
    // 獲取結果
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 格式化數據
    foreach ($orders as &$order) {
        $order['total_price'] = number_format((float)$order['total_price'], 2, '.', '');
        $order['created_at'] = date('Y-m-d H:i:s', strtotime($order['created_at']));
        $order['delivery_address'] = $order['delivery_method'] === '自取' 
            ? $order['pickup_location'] 
            : $order['address'];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $orders
    ]);

} catch (Exception $e) {
    error_log("Order List Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => '系統錯誤',
        'debug' => $e->getMessage()
    ]);
} 