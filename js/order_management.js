let currentOrderId = null;
let currentOrderData = null;

// 加載訂單列表
function loadOrders() {
    const status = document.getElementById('statusFilter').value;
    const tbody = document.getElementById('orderTableBody');
    
    // 顯示加載中
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">加載中...</span>
                </div>
            </td>
        </tr>
    `;

    fetch(`api/get_orders.php${status ? '?status=' + status : ''}`)
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                throw new Error(response.message || '加載失敗');
            }

            tbody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">暫無訂單數據</div>
                        </td>
                    </tr>
                `;
                return;
            }

            response.data.forEach(order => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${order.id}</td>
                    <td>${order.user_name || order.user_id}</td>
                    <td>${order.delivery_method}</td>
                    <td>${order.payment_method}</td>
                    <td>$${order.total_price}</td>
                    <td>
                        <span class="badge bg-${getStatusBadgeColor(order.status)}">
                            ${order.status}
                        </span>
                    </td>
                    <td>${order.created_at}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewOrderDetail(${order.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-danger">
                            加載訂單列表失敗<br>
                            <small class="text-muted">${error.message}</small>
                        </div>
                    </td>
                </tr>
            `;
        });
}

// 查看訂單詳情
function viewOrderDetail(orderId) {
    currentOrderId = orderId;
    fetch(`api/get_order_details.php?id=${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentOrderData = data.data;
                
                // 填充基本信息
                document.getElementById('orderBasicInfo').innerHTML = `
                    <p><strong>訂單編號：</strong>${currentOrderData.id}</p>
                    <p><strong>用戶名稱：</strong>${currentOrderData.user_name || '-'}</p>
                    <p><strong>聯繫電話：</strong>${currentOrderData.phone || '-'}</p>
                    <p><strong>配送方式：</strong>${currentOrderData.delivery_method}</p>
                    <p><strong>配送地址：</strong>${currentOrderData.delivery_method === '自取' ? 
                        currentOrderData.pickup_location : currentOrderData.address || '-'}</p>
                    <p><strong>付款方式：</strong>${currentOrderData.payment_method}</p>
                    <p><strong>訂單狀態：</strong>${currentOrderData.status}</p>
                    <p><strong>下單時間：</strong>${currentOrderData.created_at}</p>
                    <p><strong>總金額：</strong>$${currentOrderData.total_price}</p>
                `;

                // 填充商品列表
                const tbody = document.getElementById('orderItemsBody');
                tbody.innerHTML = '';
                currentOrderData.items.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${item.product_name}</td>
                        <td>${item.quantity}</td>
                        <td>$${parseFloat(item.price).toFixed(2)}</td>
                        <td>$${(item.quantity * item.price).toFixed(2)}</td>
                    `;
                    tbody.appendChild(tr);
                });

                // 設置當前狀態
                document.getElementById('orderStatus').value = currentOrderData.status;

                // 顯示模態框
                new bootstrap.Modal(document.getElementById('orderDetailModal')).show();
            } else {
                alert(data.message || '加載訂單詳情失敗');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('加載訂單詳情失敗');
        });
}

// 更新訂單狀態
function updateOrderStatus() {
    const newStatus = document.getElementById('orderStatus').value;
    
    fetch('api/update_order_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            orderId: currentOrderId,
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('狀態更新成功');
            loadOrders();
            bootstrap.Modal.getInstance(document.getElementById('orderDetailModal')).hide();
        } else {
            alert(data.message || '更新失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 格式化日期
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleString('zh-TW', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}

// 獲取狀態文字
function getStatusText(status) {
    const statusMap = {
        'pending': '待處理',
        'processing': '處理中',
        'completed': '已完成',
        'cancelled': '已取消'
    };
    return statusMap[status] || status;
}

// 工具函數：獲取狀態對應的顏色
function getStatusBadgeColor(status) {
    switch (status) {
        case '待確認': return 'warning';
        case '配送中': return 'info';
        case '到達自取點': return 'primary';
        case '已完成': return 'success';
        default: return 'secondary';
    }
}

// 事件監聽器
document.addEventListener('DOMContentLoaded', () => {
    loadOrders();
    
    // 狀態篩選
    document.getElementById('statusFilter').addEventListener('change', loadOrders);
    
    // 搜索功能
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchText = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#orderTableBody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });
}); 