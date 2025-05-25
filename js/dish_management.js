let currentDishId = null;
let isEditing = false;

// 加載菜式列表
function loadDishes() {
    fetch('api/get_dishes.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('dishTableBody');
            tbody.innerHTML = '';
            
            data.forEach(dish => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${dish.id}</td>
                    <td>${dish.name}</td>
                    <td>
                        <span class="badge bg-${dish.type === '素' ? 'success' : 'primary'}">
                            ${dish.type}
                        </span>
                    </td>
                    <td>${dish.profession_tag || '-'}</td>
                    <td>${dish.products ? dish.products.join(', ') : '-'}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary btn-icon me-1" 
                                    onclick="editDish(${dish.id})" 
                                    title="編輯">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-icon" 
                                    onclick="deleteDish(${dish.id})"
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
            alert('加載菜式列表失敗');
        });
}

// 加載產品選項
function loadProducts() {
    fetch('api/get_products.php')
        .then(response => response.json())
        .then(data => {
            const select = $('#productSelect');
            select.empty();

            // 添加產品選項
            data.forEach(product => {
                const option = new Option(product.name, product.id);
                option.dataset.price = product.price;
                option.dataset.stock = product.stock;
                option.dataset.type = product.type || '';
                select.append(option);
            });

            // 初始化 Select2
            select.select2({
                placeholder: '點擊這裡選擇產品',
                allowClear: true,
                templateResult: formatProduct,
                templateSelection: formatSelection,
                language: {
                    noResults: () => '沒有找到相關產品',
                    searching: () => '搜索中...'
                },
                width: '100%',
                dropdownParent: $('#addDishModal .modal-body'),
                closeOnSelect: false,
                multiple: true,
                maximumSelectionLength: 10,
                minimumInputLength: 0,
                dropdownCssClass: 'select2-dropdown-above',
                containerCssClass: 'select2-container--above'
            }).on('select2:open', function() {
                setTimeout(() => {
                    $('.select2-search__field').attr('placeholder', '輸入產品名稱搜索...');
                }, 0);
            });

            // 修復搜索框寬度
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').style.width = '100%';
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('加載產品列表失敗');
        });
}

// 格式化產品選項
function formatProduct(product) {
    if (!product.id) {
        return product.text;
    }

    const element = product.element;
    const price = parseFloat(element.dataset.price) || 0;
    const stock = parseInt(element.dataset.stock) || 0;
    const type = getTypeLabel(element.dataset.type);

    return $(`
        <div class="product-item">
            <div class="product-info">
                <strong>${product.text}</strong>
                <span class="product-price">$${price.toFixed(2)}</span>
                <small class="text-muted">${type}</small>
            </div>
            <span class="product-stock ${stock < 10 ? 'stock-low' : 'stock-ok'}">
                庫存: ${stock}
            </span>
        </div>
    `);
}

// 格式化已選產品
function formatSelection(product) {
    if (!product.id) {
        return product.text;
    }
    
    const price = parseFloat(product.element.dataset.price) || 0;
    return `${product.text} ($${price.toFixed(2)})`;
}

// 搜索功能
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#dishTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
    });
});

// 編輯菜式
function editDish(dishId) {
    currentDishId = dishId;
    isEditing = true;
    
    fetch(`api/get_dish.php?id=${dishId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const form = document.getElementById('dishForm');
                const dish = data.dish;
                
                // 設置基本信息
                form.querySelector('[name="id"]').value = dish.id;
                form.querySelector('[name="name"]').value = dish.name;
                form.querySelector('[name="type"]').value = dish.type;
                form.querySelector('[name="profession_tag"]').value = dish.profession_tag || '';
                form.querySelector('[name="recipe"]').value = dish.recipe || '';

                // 等待 Select2 初始化完成後再設置選中的產品
                setTimeout(() => {
                    const productSelect = $('#productSelect');
                    
                    // 清除之前的選擇
                    productSelect.val(null).trigger('change');
                    
                    // 設置新的選擇
                    if (Array.isArray(dish.products)) {
                        productSelect.val(dish.products).trigger('change');
                    }
                    
                    // 顯示模態框
                    document.querySelector('#addDishModal .modal-title').textContent = '編輯菜式';
                    new bootstrap.Modal(document.getElementById('addDishModal')).show();
                }, 100);
            } else {
                alert(data.message || '加載菜式信息失敗');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('系統錯誤');
        });
}

// 刪除菜式
function deleteDish(dishId) {
    if (!confirm('確定要刪除這個菜式嗎？')) {
        return;
    }

    fetch('api/delete_dish.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ dishId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('菜式已刪除');
            loadDishes();
        } else {
            alert(data.message || '刪除失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 保存菜式
document.getElementById('saveDishBtn').addEventListener('click', function() {
    const form = document.getElementById('dishForm');
    const formData = new FormData(form);
    
    // 處理多選的產品
    const products = Array.from(form.querySelector('[name="products[]"]').selectedOptions)
        .map(option => option.value);
    formData.delete('products[]');
    products.forEach(productId => formData.append('products[]', productId));
    
    const url = isEditing ? 'api/update_dish.php' : 'api/add_dish.php';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(isEditing ? '菜式已更新' : '菜式已創建');
            bootstrap.Modal.getInstance(document.getElementById('addDishModal')).hide();
            form.reset();
            loadDishes();
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
document.getElementById('addDishModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('dishForm').reset();
    document.querySelector('#addDishModal .modal-title').textContent = '新增菜式';
    currentDishId = null;
    isEditing = false;
});

// 類型標籤轉換
function getTypeLabel(type) {
    const labels = {
        'food': '食品',
        'vegetable': '蔬菜',
        'drink': '飲品',
        'life': '生活用品'
    };
    return labels[type] || type;
}

// 頁面加載時獲取菜式列表和產品列表
document.addEventListener('DOMContentLoaded', () => {
    loadDishes();
    loadProducts();
}); 