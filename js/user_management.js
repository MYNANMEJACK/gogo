// 加載用戶列表
function loadUsers() {
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
                        <button class="btn btn-sm btn-primary me-1" onclick="editUser(${user.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-${user.status === 'active' ? 'warning' : 'success'}" 
                                onclick="toggleUserStatus(${user.id}, '${user.status}')">
                            <i class="fas fa-${user.status === 'active' ? 'ban' : 'check'}"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">
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
            loadUsers();
        } else {
            alert(data.message || '創建失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
});

// 切換用戶狀態
function toggleUserStatus(userId, currentStatus) {
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
            loadUsers();
        } else {
            alert(data.message || '更新失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 頁面加載時獲取用戶列表
document.addEventListener('DOMContentLoaded', loadUsers); 