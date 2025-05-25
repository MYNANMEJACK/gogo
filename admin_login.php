<?php
session_start();

// 添加錯誤日誌
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 數據庫連接配置
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
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['rememberMe']);

        // 添加調試信息
        error_log("Login attempt - Username: $username");

        // 查詢用戶
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND status = 'active'");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // 添加調試信息
        error_log("User found: " . ($admin ? 'Yes' : 'No'));
        if ($admin) {
            error_log("Password verification: " . (password_verify($password, $admin['password']) ? 'Success' : 'Failed'));
        }

        if ($admin && password_verify($password, $admin['password'])) {
            // 登錄成功
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_role'] = $admin['role'];

            // 更新最後登錄時間
            $updateStmt = $pdo->prepare("UPDATE admins SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $updateStmt->execute([$admin['id']]);

            // 如果選擇了"記住我"
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $updateToken = $pdo->prepare("UPDATE admins SET remember_token = ? WHERE id = ?");
                $updateToken->execute([$token, $admin['id']]);
                
                // 設置 cookie，30天有效期
                setcookie('admin_remember', $token, time() + (86400 * 30), '/', '', true, true);
            }

            echo json_encode(['success' => true, 'message' => '登錄成功']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => '用戶名或密碼錯誤']);
            exit;
        }
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '系統錯誤: ' . $e->getMessage()]);
    exit;
}
?> 