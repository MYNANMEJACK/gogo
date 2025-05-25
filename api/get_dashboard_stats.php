<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    // 獲取總訂單數
    $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
    $totalOrders = $stmt->fetchColumn();

    // 獲取總銷售額
    $stmt = $pdo->query("SELECT SUM(total_price) FROM orders");
    $totalSales = number_format($stmt->fetchColumn(), 2);

    // 獲取客戶數量
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $totalCustomers = $stmt->fetchColumn();

    // 獲取產品數量
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $totalProducts = $stmt->fetchColumn();

    echo json_encode([
        'totalOrders' => $totalOrders,
        'totalSales' => $totalSales,
        'totalCustomers' => $totalCustomers,
        'totalProducts' => $totalProducts
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 