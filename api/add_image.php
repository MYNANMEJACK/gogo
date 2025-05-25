<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $url = $_POST['url'] ?? '';

    // 驗證URL格式
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        echo json_encode(['success' => false, 'message' => '無效的URL格式']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO images (url) VALUES (?)");
    $stmt->execute([$url]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
} 