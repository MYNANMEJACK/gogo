<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $name = $_POST['name'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $origin = $_POST['origin'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $type = $_POST['type'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO products (name, image_url, description, price, origin, tags, stock, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $name,
        $image_url,
        $description,
        $price,
        $origin,
        $tags,
        $stock,
        $type
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤: ' . $e->getMessage()]);
} 