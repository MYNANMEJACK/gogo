// 加載客戶列表
function loadCustomers() {
    fetch('api/get_customers.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            // 檢查響應的 content-type
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new TypeError("返回的數據不是 JSON 格式!");
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.getElementById('customerTableBody');
            tbody.innerHTML = '';
            
            // 檢查返回的數據
            if (!Array.isArray(data)) {
                // 如果數據包含在 data 字段中
                if (data.data && Array.isArray(data.data)) {
                    data = data.data;
                } else {
                    throw new Error('返回的數據格式不正確');
                }
            }
            
            data.forEach(customer => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${customer.id}</td>
                    <td>${customer.name}</td>
                    <td>${customer.email}</td>
                    <td>${customer.phone || '-'}</td>
                    <td>${customer.job || '-'}</td>
                    <td>${customer.gender === 'male' ? '男' : '女'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger me-1" onclick="deleteCustomer(${customer.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="resetPassword(${customer.id})">
                            <i class="fas fa-key"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('加載客戶數據時發生錯誤:', error);
            const tbody = document.getElementById('customerTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        加載數據失敗: ${error.message}
                    </td>
                </tr>
            `;
        });
}

// 搜索功能
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#customerTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
    });
});

// 刪除客戶
function deleteCustomer(customerId) {
    if (!confirm('確定要刪除該客戶嗎？此操作無法撤銷。')) {
        return;
    }

    fetch('api/delete_user_account.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ customerId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('客戶已成功刪除');
            loadCustomers();
        } else {
            alert(data.message || '刪除失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 重置密碼
function resetPassword(customerId) {
    if (!confirm('確定要重置該客戶的密碼嗎？')) {
        return;
    }

    fetch('api/reset_user_password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ customerId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || '密碼已成功重置');
        } else {
            alert(data.message || '重置失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 頁面加載時獲取客戶列表
document.addEventListener('DOMContentLoaded', loadCustomers); 