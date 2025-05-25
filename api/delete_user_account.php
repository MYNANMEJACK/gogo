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

    $pdo->beginTransaction();

    // 刪除用戶相關的訂單記錄
    $stmt = $pdo->prepare("DELETE FROM orders WHERE user_id = ?");
    $stmt->execute([$userId]);

    // 刪除用戶記錄
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('用戶不存在');
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 