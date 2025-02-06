<?php
session_start();
// 嚴格檢查：必須是超級管理員
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    header('Location: admin_dashboard.php');  // 重定向到儀表板
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用戶管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-page">
    <div class="container-fluid">
        <div class="row">
            <!-- 側邊欄 -->
            <?php include 'includes/sidebar.php'; ?>

            <!-- 主要內容區 -->
            <div class="col-md-9 col-lg-10 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>員工管理</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-2"></i>新增員工
                    </button>
                </div>

                <!-- 用戶列表 -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>員工帳號</th>
                                        <th>郵箱</th>
                                        <th>姓名</th>
                                        <th>角色</th>
                                        <th>狀態</th>
                                        <th>最後登錄</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody id="userTableBody">
                                    <!-- 用戶數據將通過 JavaScript 動態加載 -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 新增用戶模態框 -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">新增員工</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="mb-3">
                            <label class="form-label">員工帳號</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">密碼</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">郵箱</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">姓名</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">角色</label>
                            <select class="form-select" name="role" required>
                                <option value="admin">一般員工</option>
                                <option value="super_admin">管理員</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="saveUserBtn">保存</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 編輯用戶模態框 -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">編輯員工</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" name="userId" id="editUserId">
                        <div class="mb-3">
                            <label class="form-label">員工帳號</label>
                            <input type="text" class="form-control" name="username" id="editUsername" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">郵箱</label>
                            <input type="email" class="form-control" name="email" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">姓名</label>
                            <input type="text" class="form-control" name="full_name" id="editFullName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">角色</label>
                            <select class="form-select" name="role" id="editRole" required>
                                <option value="admin">一般員工</option>
                                <option value="super_admin">管理員</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" onclick="window.updateUser()">保存</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 先加載 Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- 確保 user_management.js 在最後加載 -->
    <script>
        // 先定義全局函數
        window.deleteUser = function(userId) {
            if (!confirm('確定要刪除這個用戶嗎？')) {
                return;
            }

            fetch('api/delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    userId: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('用戶已成功刪除');
                    window.loadUsers();
                } else {
                    alert(data.message || '刪除失敗');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('系統錯誤');
            });
        };

        // 添加編輯用戶的全局函數
        window.editUser = function(userId) {
            // 獲取用戶數據
            fetch(`api/get_user.php?id=${userId}`)
                .then(response => response.json())
                .then(user => {
                    // 填充表單
                    document.getElementById('editUserId').value = user.id;
                    document.getElementById('editUsername').value = user.username;
                    document.getElementById('editEmail').value = user.email;
                    document.getElementById('editFullName').value = user.full_name;
                    document.getElementById('editRole').value = user.role;
                    
                    // 顯示模態框
                    new bootstrap.Modal(document.getElementById('editUserModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('獲取用戶數據失敗');
                });
        };

        // 添加更新用戶的全局函數
        window.updateUser = function() {
            console.log('Update function called');
            
            const form = document.getElementById('editUserForm');
            const formData = new FormData(form);

            console.log('Form data:', {
                userId: formData.get('userId'),
                username: formData.get('username'),
                email: formData.get('email'),
                full_name: formData.get('full_name'),
                role: formData.get('role')
            });

            fetch('api/update_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    alert('用戶更新成功');
                    bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                    window.loadUsers();
                } else {
                    alert(data.message || '更新失敗');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('系統錯誤');
            });
        };
    </script>
    <script src="js/user_management.js"></script>
</body>
</html> 