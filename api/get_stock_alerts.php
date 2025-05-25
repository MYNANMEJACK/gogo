<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    // 獲取庫存預警數據
    $stmt = $pdo->query("
        SELECT 
            id,
            name,
            price,
            stock,
            CASE 
                WHEN stock = 0 THEN 'out'
                WHEN stock <= 5 THEN 'low'
                ELSE 'normal'
            END as status
        FROM products 
        WHERE stock <= 5
        ORDER BY stock ASC
    ");
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stockOut = array_filter($products, function($p) {
        return $p['stock'] == 0;
    });
    
    $lowStock = array_filter($products, function($p) {
        return $p['stock'] > 0 && $p['stock'] <= 5;
    });
    
    echo json_encode([
        'success' => true,
        'data' => [
            'stockOut' => array_values($stockOut),
            'lowStock' => array_values($lowStock)
        ]
    ]);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => '系統錯誤',
        'error' => $e->getMessage()
    ]);
} 