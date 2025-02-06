<?php
// 開啟錯誤報告
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $host = 'localhost';
    $dbname = 'gogoapp';  // 您的數據庫名稱
    $username = 'root';    // 您的數據庫用戶名
    $password = '';       // 您的數據庫密碼

    // 創建 PDO 實例
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("數據庫連接失敗: " . $e->getMessage());
}
?> 