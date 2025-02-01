<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    // 獲取產品銷售統計
    $stmt = $pdo->query("
        SELECT 
            p.id,
            p.name,
            p.price,
            COALESCE(SUM(oi.quantity), 0) as quantity,
            COALESCE(SUM(oi.quantity * p.price), 0) as total_sales
        FROM products p
        LEFT JOIN (
            SELECT product_name, quantity 
            FROM order_items oi 
            JOIN orders o ON oi.order_id = o.id 
            WHERE o.status != 'cancelled'
        ) oi ON p.name = oi.product_name
        GROUP BY p.id, p.name, p.price
        ORDER BY total_sales DESC
        LIMIT 10
    ");
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($result)) {
        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => '暫無銷售數據'
        ]);
        exit;
    }
    
    // 格式化數據
    foreach ($result as &$item) {
        $item['total_sales'] = floatval($item['total_sales']);
        $item['price'] = floatval($item['price']);
        $item['quantity'] = intval($item['quantity']);
    }
    
    echo json_encode([
        'success' => true,
        'data' => $result
    ]);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => '系統錯誤',
        'error' => $e->getMessage()
    ]);
} 