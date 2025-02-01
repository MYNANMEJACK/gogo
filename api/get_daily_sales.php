<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    // 獲取最近90天的每日銷售額
    $stmt = $pdo->query("
        SELECT 
            DATE(created_at) as date,
            SUM(total_price) as total_sales,
            COUNT(*) as order_count
        FROM orders 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
        AND status != 'cancelled'
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 格式化數據
    foreach ($result as &$item) {
        $item['total_sales'] = floatval($item['total_sales']);
        $item['order_count'] = intval($item['order_count']);
        // 格式化日期為更友好的格式
        $item['date'] = date('m/d', strtotime($item['date']));
    }
    
    // 如果某天沒有訂單，填充0
    $endDate = new DateTime();
    $startDate = new DateTime('-90 days');
    $allDates = [];
    
    while ($startDate <= $endDate) {
        $dateStr = $startDate->format('m/d');
        $found = false;
        
        foreach ($result as $item) {
            if ($item['date'] === $dateStr) {
                $allDates[] = $item;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $allDates[] = [
                'date' => $dateStr,
                'total_sales' => 0,
                'order_count' => 0
            ];
        }
        
        $startDate->modify('+1 day');
    }
    
    echo json_encode([
        'success' => true,
        'data' => $allDates
    ]);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => '系統錯誤',
        'error' => $e->getMessage()
    ]);
} 