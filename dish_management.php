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
    <title>菜式管理</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="styles.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Dish Management CSS -->
    <link href="css/dish_management.css" rel="stylesheet">
    
    <style>
        /* 表格樣式 */
        .table > :not(caption) > * > * {
            vertical-align: middle;
        }
        
        /* 按鈕樣式 */
        .btn-icon {
            padding: 0.5rem;
            line-height: 1;
            border-radius: 6px;
        }
        
        /* 模態框樣式 */
        .modal-header {
            background: linear-gradient(135deg, #0062ff 0%, #0056e0 100%);
            color: white;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        /* Select2 容器樣式 */
        .select2-container {
            width: 100% !important;
        }
        
        /* 修復模態框中的 Select2 */
        .modal-dialog {
            z-index: 1050;
        }
        
        .select2-container--open {
            z-index: 9999999 !important;
        }
        
        .select2-dropdown {
            z-index: 9999999 !important;
        }
        
        /* 選擇框樣式 */
        .select2-container .select2-selection--multiple {
            min-height: 100px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        /* 下拉容器樣式 */
        .select2-container--default .select2-results > .select2-results__options {
            max-height: 400px;
        }
        
        /* 選項樣式 */
        .select2-results__option {
            padding: 8px 12px !important;
        }
        
        /* 選中標籤樣式 */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0062ff;
            border: none;
            color: white;
            border-radius: 4px;
            padding: 4px 8px;
            margin: 4px;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
            border: none;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        /* 產品選項樣式 */
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .product-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .product-price {
            color: #0062ff;
            font-weight: 500;
        }
        
        .product-stock {
            font-size: 0.9em;
            padding: 2px 8px;
            border-radius: 12px;
        }
        
        .stock-ok {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .stock-low {
            background: #ffebee;
            color: #c62828;
        }
        
        /* 修復模態框內的滾動問題 */
        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
</head>
<body class="admin-page">
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <div class="col-md-9 col-lg-10 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>菜式管理</h2>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="search-box">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="搜索菜式...">
                            </div>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDishModal">
                            <i class="fas fa-plus me-2"></i>新增菜式
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>名稱</th>
                                        <th>類型</th>
                                        <th>標籤</th>
                                        <th>食材</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody id="dishTableBody">
                                    <!-- 數據將通過 JavaScript 動態加載 -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 新增/編輯菜式模態框 -->
    <div class="modal fade" id="addDishModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">新增菜式</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="position: static;">
                    <form id="dishForm">
                        <input type="hidden" name="id" id="dishId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">菜式名稱</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">類型</label>
                                <select class="form-select" name="type" required>
                                    <option value="素">素食</option>
                                    <option value="肉">葷食</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">標籤</label>
                                <input type="text" class="form-control" name="profession_tag" placeholder="例如：中式、西式">
                            </div>
                            <div class="col-12">
                                <label class="form-label">食譜說明</label>
                                <textarea class="form-control" name="recipe" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">選擇食材（可多選）</label>
                                <select class="form-select" name="products[]" id="productSelect" multiple>
                                    <!-- 產品選項將通過 JavaScript 動態加載 -->
                                </select>
                                <small class="text-muted">可以輸入名稱或類型搜索</small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="saveDishBtn">保存</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="js/dish_management.js"></script>
</body>
</html> 