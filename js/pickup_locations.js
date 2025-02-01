let currentLocationId = null;
let isEditing = false;

// 加載自提點列表
function loadLocations() {
    fetch('api/get_pickup_locations.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('locationTableBody');
            tbody.innerHTML = '';
            
            data.forEach(location => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${location.id}</td>
                    <td>${location.address}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary btn-icon me-1" 
                                    onclick="editLocation(${location.id})" 
                                    title="編輯">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-icon" 
                                    onclick="deleteLocation(${location.id})"
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
            alert('加載自提點列表失敗');
        });
}

// 搜索功能
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#locationTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
    });
});

// 編輯自提點
function editLocation(locationId) {
    currentLocationId = locationId;
    isEditing = true;
    
    fetch(`api/get_pickup_location.php?id=${locationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const form = document.getElementById('locationForm');
                const location = data.location;
                
                form.querySelector('[name="id"]').value = location.id;
                form.querySelector('[name="address"]').value = location.address;

                document.querySelector('#addLocationModal .modal-title').textContent = '編輯自提點';
                new bootstrap.Modal(document.getElementById('addLocationModal')).show();
            } else {
                alert(data.message || '加載自提點信息失敗');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('系統錯誤');
        });
}

// 刪除自提點
function deleteLocation(locationId) {
    if (!confirm('確定要刪除這個自提點嗎？')) {
        return;
    }

    fetch('api/delete_pickup_location.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ locationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('自提點已刪除');
            loadLocations();
        } else {
            alert(data.message || '刪除失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 保存自提點
document.getElementById('saveLocationBtn').addEventListener('click', function() {
    const form = document.getElementById('locationForm');
    const formData = new FormData(form);
    
    const url = isEditing ? 'api/update_pickup_location.php' : 'api/add_pickup_location.php';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(isEditing ? '自提點已更新' : '自提點已創建');
            bootstrap.Modal.getInstance(document.getElementById('addLocationModal')).hide();
            form.reset();
            loadLocations();
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
document.getElementById('addLocationModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('locationForm').reset();
    document.querySelector('#addLocationModal .modal-title').textContent = '新增自提點';
    currentLocationId = null;
    isEditing = false;
});

// 頁面加載時獲取自提點列表
document.addEventListener('DOMContentLoaded', loadLocations); 