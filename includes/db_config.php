<?php
$db_config = [
    'host' => 'localhost',
    'dbname' => 'gogoapp',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    // 返回 JSON 格式的錯誤信息
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => '數據庫連接失敗',
        'error' => $e->getMessage()
    ]);
    exit;
} 