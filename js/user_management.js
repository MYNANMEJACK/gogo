// 加載用戶列表
window.loadUsers = function() {
    fetch('api/get_users.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = '';
            
            data.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.full_name}</td>
                    <td>${user.role === 'super_admin' ? '超級管理員' : '管理員'}</td>
                    <td>
                        <span class="badge bg-${user.status === 'active' ? 'success' : 'danger'}">
                            ${user.status === 'active' ? '啟用' : '禁用'}
                        </span>
                    </td>
                    <td>${user.last_login || '從未登錄'}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="window.editUser(${user.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-${user.status === 'active' ? 'warning' : 'success'}" 
                                onclick="window.toggleUserStatus(${user.id}, '${user.status}')">
                            <i class="fas fa-${user.status === 'active' ? 'ban' : 'check'}"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="window.deleteUser(${user.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('加載用戶列表失敗');
        });
}

// 切換用戶狀態
window.toggleUserStatus = function(userId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    
    fetch('api/update_user_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            userId: userId,
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.loadUsers();
        } else {
            alert(data.message || '更新失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 刪除用戶
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
}

// 編輯用戶
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
}

// 頁面加載完成後的初始化
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    // 加載用戶列表
    window.loadUsers();

    // 保存新用戶
    document.getElementById('saveUserBtn').addEventListener('click', function() {
        const form = document.getElementById('addUserForm');
        const formData = new FormData(form);

        fetch('api/add_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('用戶創建成功');
                bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                form.reset();
                window.loadUsers();
            } else {
                alert(data.message || '創建失敗');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('系統錯誤');
        });
    });
}); 