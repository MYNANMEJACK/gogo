// 加載客戶列表
function loadCustomers() {
    fetch('api/get_customers.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('customerTableBody');
            tbody.innerHTML = '';
            
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
            console.error('Error:', error);
            alert('加載客戶列表失敗');
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

    fetch('api/delete_customer.php', {
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
            loadCustomers(); // 重新加載客戶列表
        } else {
            alert(data.message || '刪除失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 頁面加載時獲取客戶列表
document.addEventListener('DOMContentLoaded', loadCustomers); 