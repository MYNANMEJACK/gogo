<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $id = $_POST['id'] ?? 0;
    $address = $_POST['address'] ?? '';

    $stmt = $pdo->prepare("UPDATE pickup_locations SET address = ? WHERE id = ?");
    $stmt->execute([$address, $id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 