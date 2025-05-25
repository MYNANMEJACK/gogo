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
    <title>產品管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link href="css/product_management.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-page">
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <div class="col-md-9 col-lg-10 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>產品管理</h2>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="dropdown">
                            <select id="typeFilter" class="form-select" style="min-width: 150px;">
                                <option value="">所有類型</option>
                                <option value="food">食品</option>
                                <option value="vegetable">蔬菜</option>
                                <option value="drink">飲品</option>
                                <option value="life">生活用品</option>
                            </select>
                        </div>
                        <div class="search-box">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="搜索產品...">
                            </div>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus me-2"></i>新增產品
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
                                        <th>圖片</th>
                                        <th>名稱</th>
                                        <th>價格</th>
                                        <th>庫存</th>
                                        <th>類型</th>
                                        <th>標籤</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody id="productTableBody">
                                    <!-- 數據將通過 JavaScript 動態加載 -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 新增/編輯產品模態框 -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">新增產品</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" name="id" id="productId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">產品名稱</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">圖片URL</label>
                                <input type="text" class="form-control" name="image_url" id="imageUrl" required>
                                <img id="imagePreview" class="preview-image d-none">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">價格</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="price" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">庫存</label>
                                <input type="number" class="form-control" name="stock" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">類型</label>
                                <select class="form-select" name="type" required>
                                    <option value="food">食品</option>
                                    <option value="vegetable">蔬菜</option>
                                    <option value="drink">飲品</option>
                                    <option value="life">生活用品</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">產地</label>
                                <input type="text" class="form-control" name="origin" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">標籤</label>
                                <input type="text" class="form-control" name="tags" placeholder="用逗號分隔多個標籤">
                            </div>
                            <div class="col-12">
                                <label class="form-label">商品描述</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="saveProductBtn">保存</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/product_management.js"></script>
    <script>
        // 圖片預覽功能
        document.getElementById('imageUrl').addEventListener('input', function(e) {
            const preview = document.getElementById('imagePreview');
            const url = e.target.value;
            if (url) {
                preview.src = url;
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
        });
    </script>
</body>
</html> 