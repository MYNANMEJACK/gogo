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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link href="css/admin_dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.css" rel="stylesheet">
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

                <!-- 統計卡片 -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted">總訂單數</h6>
                                        <h3 id="totalOrders">-</h3>
                                    </div>
                                    <div class="stats-icon bg-primary text-white">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted">總銷售額</h6>
                                        <h3 id="totalSales">-</h3>
                                    </div>
                                    <div class="stats-icon bg-success text-white">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted">客戶數量</h6>
                                        <h3 id="totalCustomers">-</h3>
                                    </div>
                                    <div class="stats-icon bg-info text-white">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted">產品數量</h6>
                                        <h3 id="totalProducts">-</h3>
                                    </div>
                                    <div class="stats-icon bg-warning text-white">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 在統計卡片區域後添加 -->
                <div class="row mb-4">
                    <!-- 缺貨預警 -->
                    <div class="col-md-6">
                        <div class="card alert-card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>缺貨預警
                                </h5>
                            </div>
                            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                <div id="stockOutAlert">
                                    <div class="text-center py-3">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">加載中...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 低庫存預警 -->
                    <div class="col-md-6">
                        <div class="card alert-card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-exclamation-circle me-2"></i>低庫存預警
                                </h5>
                            </div>
                            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                <div id="lowStockAlert">
                                    <div class="text-center py-3">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">加載中...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 修改圖表區域的佈局 -->
                <div class="row">
                    <!-- 產品銷售統計 -->
                    <div class="col-md-6 mb-4">
                        <div class="card chart-card">
                            <div class="card-body">
                                <h5 class="card-title">熱門產品銷售統計</h5>
                                <div id="productSalesChart"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 訂單狀態分布 -->
                    <div class="col-md-6 mb-4">
                        <div class="card chart-card">
                            <div class="card-body">
                                <h5 class="card-title">訂單狀態分布</h5>
                                <div id="orderStatusChart"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 每日銷售金額統計 -->
                    <div class="col-md-12">
                        <div class="card chart-card">
                            <div class="card-body">
                                <h5 class="card-title">每日銷售金額統計</h5>
                                <div id="dailySalesChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ApexCharts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html> 