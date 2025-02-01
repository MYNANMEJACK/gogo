<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $locationId = $_GET['id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM pickup_locations WHERE id = ?");
    $stmt->execute([$locationId]);
    $location = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$location) {
        echo json_encode(['success' => false, 'message' => '自提點不存在']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'location' => $location
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 