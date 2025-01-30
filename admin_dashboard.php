<?php
session_start();

// 檢查是否已登錄
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit;
}

// 獲取管理員信息
$admin_username = $_SESSION['admin_username'];
$admin_role = $_SESSION['admin_role'];
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理面板</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
        }
        .nav-link {
            color: rgba(255,255,255,.8);
        }
        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
        }
        .nav-link.active {
            background: rgba(255,255,255,.2);
            color: white;
        }
        .content {
            padding: 20px;
        }
        .welcome-card {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
        }
    </style>
</head>
<body class="admin-page">
    <div class="container-fluid">
        <div class="row">
            <!-- 側邊欄 -->
            <?php include 'includes/sidebar.php'; ?>

            <!-- 主要內容區 -->
            <div class="col-md-9 col-lg-10 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>管理面板</h2>
                    <div class="user-info">
                        <i class="fas fa-user me-2"></i>
                        <?php echo htmlspecialchars($admin_username); ?> 
                        (<?php echo htmlspecialchars($admin_role); ?>)
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card welcome-card">
                            <div class="card-body">
                                <h5 class="card-title">歡迎回來！</h5>
                                <p class="card-text">上次登錄時間：<?php echo date('Y-m-d H:i:s'); ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- 可以添加更多卡片和功能 -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 