let currentImageId = null;
let isEditing = false;

// 加載圖片列表
function loadImages() {
    fetch('api/get_images.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('imageTableBody');
            tbody.innerHTML = '';
            
            data.forEach(image => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${image.id}</td>
                    <td>
                        <img src="${image.url}" alt="預覽" class="image-preview" 
                            onerror="this.src='assets/img/no-image.png'">
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 300px;">
                            ${image.url}
                        </div>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary btn-icon me-1" 
                                    onclick="editImage(${image.id})" 
                                    title="編輯">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-icon" 
                                    onclick="deleteImage(${image.id})"
                                    title="刪除">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info btn-icon ms-1" 
                                    onclick="copyUrl('${image.url}')"
                                    title="複製URL">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('加載圖片列表失敗');
        });
}

// 複製URL到剪貼板
function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('URL已複製到剪貼板');
    }).catch(err => {
        console.error('複製失敗:', err);
    });
}

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

// 搜索功能
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#imageTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
    });
});

// 編輯圖片
function editImage(imageId) {
    currentImageId = imageId;
    isEditing = true;
    
    fetch(`api/get_image.php?id=${imageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const form = document.getElementById('imageForm');
                const image = data.image;
                
                form.querySelector('[name="id"]').value = image.id;
                form.querySelector('[name="url"]').value = image.url;
                
                // 顯示預覽
                const preview = document.getElementById('imagePreview');
                preview.src = image.url;
                preview.classList.remove('d-none');

                document.querySelector('#addImageModal .modal-title').textContent = '編輯圖片';
                new bootstrap.Modal(document.getElementById('addImageModal')).show();
            } else {
                alert(data.message || '加載圖片信息失敗');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('系統錯誤');
        });
}

// 刪除圖片
function deleteImage(imageId) {
    if (!confirm('確定要刪除這張圖片嗎？')) {
        return;
    }

    fetch('api/delete_image.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ imageId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('圖片已刪除');
            loadImages();
        } else {
            alert(data.message || '刪除失敗');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('系統錯誤');
    });
}

// 保存圖片
document.getElementById('saveImageBtn').addEventListener('click', function() {
    const form = document.getElementById('imageForm');
    const formData = new FormData(form);
    
    const url = isEditing ? 'api/update_image.php' : 'api/add_image.php';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(isEditing ? '圖片已更新' : '圖片已創建');
            bootstrap.Modal.getInstance(document.getElementById('addImageModal')).hide();
            form.reset();
            document.getElementById('imagePreview').classList.add('d-none');
            loadImages();
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
document.getElementById('addImageModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('imageForm').reset();
    document.getElementById('imagePreview').classList.add('d-none');
    document.querySelector('#addImageModal .modal-title').textContent = '新增圖片';
    currentImageId = null;
    isEditing = false;
});

// 頁面加載時獲取圖片列表
document.addEventListener('DOMContentLoaded', loadImages); 