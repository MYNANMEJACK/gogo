let currentProductId = null;
let isEditing = false;

// 加載產品列表
function loadProducts() {
    const type = document.getElementById('typeFilter').value;
    fetch(`api/get_products.php${type ? '?type=' + type : ''}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';
            
            data.forEach(product => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${product.id}</td>
                    <td>
                        <img src="${product.image_url}" alt="${product.name}" 
                            class="product-img" onerror="this.src='assets/img/no-image.png'">
                    </td>
                    <td>
                        <div class="fw-bold">${product.name}</div>
                        <small class="text-muted">${product.origin}</small>
                    </td>
                    <td>$${parseFloat(product.price).toFixed(2)}</td>
                    <td>
                        <span class="${product.stock < 10 ? 'stock-warning' : 'stock-ok'}">
                            ${product.stock}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-primary badge-custom">
                            ${getTypeLabel(product.type)}
                        </span>
                    </td>
                    <td>
                        ${product.tags ? product.tags.split(',').map(tag => 
                            `<span class="badge bg-secondary badge-custom">${tag.trim()}</span>`
                        ).join(' ') : '-'}
                    </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary btn-icon me-1" 
                                    onclick="editProduct(${product.id})" 
                                    title="編輯">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-icon" 
                                    onclick="deleteProduct(${product.id})"
                                    title="刪除">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('加載產品列表失敗');
        });
}

// 編輯產品
function editProduct(productId) {
    currentProductId = productId;
    isEditing = true;
    
    fetch(`api/get_product.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const form = document.getElementById('productForm');
                const product = data.product;
                
                // 填充表單
                form.querySelector('[name="id"]').value = product.id;
                form.querySelector('[name="name"]').value = product.name;
                form.querySelector('[name="image_url"]').value = product.image_url;
                form.querySelector('[name="price"]').value = product.price;
                form.querySelector('[name="stock"]').value = product.stock;
                form.querySelector('[name="type"]').value = product.type;
                form.querySelector('[name="origin"]').value = product.origin;
                form.querySelector('[name="tags"]').value = product.tags;
                form.querySelector('[name="description"]').value = product.description;

                // 更新模態框標題
                document.querySelector('#addProductModal .modal-title').textContent = '編輯產品';
                
                // 顯示模態框
                new bootstrap.Modal(document.getElementById('addProductModal')).show();
            } else {
                alert(data.message || '加載產品信息失敗');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('系統錯誤');
        });
}

// 刪除產品
function deleteProduct(productId) {
    if (!confirm('確定要刪除這個產品嗎？')) {
        return;
    }

    fetch('api/delete_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('產品已刪除');
            loadProducts();
        } else {
            alert(data.message || '刪除失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 保存產品
document.getElementById('saveProductBtn').addEventListener('click', function() {
    const form = document.getElementById('productForm');
    const formData = new FormData(form);
    
    const url = isEditing ? 'api/update_product.php' : 'api/add_product.php';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(isEditing ? '產品已更新' : '產品已創建');
            bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
            form.reset();
            loadProducts();
        } else {
            alert(data.message || (isEditing ? '更新失敗' : '創建失敗'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
});

// 重置表單
document.getElementById('addProductModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('productForm').reset();
    document.querySelector('#addProductModal .modal-title').textContent = '新增產品';
    currentProductId = null;
    isEditing = false;
});

// 事件監聽器
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    
    // 類型篩選
    document.getElementById('typeFilter').addEventListener('change', loadProducts);
    
    // 搜索功能
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchText = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#productTableBody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });
});

// 添加類型標籤轉換函數
function getTypeLabel(type) {
    const labels = {
        'food': '食品',
        'vegetable': '蔬菜',
        'drink': '飲品',
        'life': '生活用品'
    };
    return labels[type] || type;
} 