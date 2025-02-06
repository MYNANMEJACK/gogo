<?php
session_start();
require_once '../includes/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => '未登錄']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['customerId'] ?? 0;  // 保持與前端參數名一致

    if ($userId <= 0) {
        throw new Exception('無效的用戶ID');
    }

    // 生成新的隨機密碼
    $newPassword = substr(md5(uniqid()), 0, 8);  // 生成8位隨機密碼
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $userId]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('用戶不存在');
    }

    echo json_encode([
        'success' => true,
        'message' => "密碼已重置為：$newPassword"
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 