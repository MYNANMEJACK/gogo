<?php
require_once '../includes/db_connection.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM customers");
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($customers);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?> 