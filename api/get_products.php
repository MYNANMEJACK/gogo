<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $type = $_GET['type'] ?? '';
    $sql = "SELECT * FROM products";
    if ($type) {
        $sql .= " WHERE type = ?";
    }
    $sql .= " ORDER BY id DESC";

    $stmt = $type ? $pdo->prepare($sql) : $pdo->query($sql);
    if ($type) {
        $stmt->execute([$type]);
    }
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤: ' . $e->getMessage()]);
} 