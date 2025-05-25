<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $stmt = $pdo->query("SELECT * FROM pickup_locations ORDER BY id DESC");
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($locations);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 