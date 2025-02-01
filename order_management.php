<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訂單管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-page">
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <div class="col-md-9 col-lg-10 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>訂單管理</h2>
                    <div class="d-flex gap-3">
                        <select id="statusFilter" class="form-select">
                            <option value="">全部狀態</option>
                            <option value="待確認">待確認</option>
                            <option value="配送中">配送中</option>
                            <option value="到達自取點">到達自取點</option>
                            <option value="已完成">已完成</option>
                        </select>
                        <div class="search-box">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="搜索訂單...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>訂單編號</th>
                                        <th>客戶名稱</th>
                                        <th>配送方式</th>
                                        <th>付款方式</th>
                                        <th>總金額</th>
                                        <th>狀態</th>
                                        <th>下單時間</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody id="orderTableBody">
                                    <!-- 數據將通過 JavaScript 動態加載 -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 訂單詳情模態框 -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">訂單詳情</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="order-info mb-4">
                        <h6>基本信息</h6>
                        <div id="orderBasicInfo"></div>
                    </div>
                    <div class="order-items">
                        <h6>訂單商品</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>商品名稱</th>
                                        <th>數量</th>
                                        <th>單價</th>
                                        <th>小計</th>
                                    </tr>
                                </thead>
                                <tbody id="orderItemsBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <select id="orderStatus" class="form-select me-2">
                        <option value="待確認">待確認</option>
                        <option value="配送中">配送中</option>
                        <option value="到達自取點">到達自取點</option>
                        <option value="已完成">已完成</option>
                    </select>
                    <button type="button" class="btn btn-primary" onclick="updateOrderStatus()">更新狀態</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/order_management.js"></script>
</body>
</html> 