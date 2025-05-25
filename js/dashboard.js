// 加載統計數據
function loadStats() {
    fetch('api/get_dashboard_stats.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalOrders').textContent = data.totalOrders;
            document.getElementById('totalSales').textContent = '$' + data.totalSales;
            document.getElementById('totalCustomers').textContent = data.totalCustomers;
            document.getElementById('totalProducts').textContent = data.totalProducts;
        });
}

// 初始化產品銷售圖表
function initProductSalesChart() {
    const chartContainer = document.querySelector("#productSalesChart");
    chartContainer.innerHTML = '<div class="text-center p-3">加載中...</div>';

    fetch('api/get_product_sales.php')
        .then(response => response.json())
        .then(response => {
            console.log('銷售數據:', response);

            if (!response.success) {
                throw new Error(response.message || '獲取數據失敗');
            }

            if (!response.data || response.data.length === 0) {
                chartContainer.innerHTML = '<div class="text-center p-4">暫無銷售數據</div>';
                return;
            }

            const data = response.data;
            const options = {
                series: data.map(item => item.quantity),
                chart: {
                    type: 'pie',
                    height: 400,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                labels: data.map(item => item.name),
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return `${val.toFixed(1)}%`;
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center'
                },
                tooltip: {
                    enabled: false
                },
                colors: [
                    '#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0',
                    '#3F51B5', '#546E7A', '#D4526E', '#8D5B4C', '#F86624'
                ],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            chartContainer.innerHTML = '';

            try {
                const chart = new ApexCharts(chartContainer, options);
                chart.render();
            } catch (error) {
                console.error('圖表渲染錯誤:', error);
                chartContainer.innerHTML = '<div class="text-center p-4">圖表加載失敗</div>';
            }
        })
        .catch(error => {
            console.error('數據加載錯誤:', error);
            chartContainer.innerHTML = `<div class="text-center p-4">數據加載失敗: ${error.message}</div>`;
        });
}

// 初始化訂單狀態圖表
function initOrderStatusChart() {
    fetch('api/get_order_status_stats.php')
        .then(response => response.json())
        .then(data => {
            const options = {
                series: data.map(item => item.count),
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: data.map(item => item.status),
                colors: ['#ffc107', '#17a2b8', '#28a745', '#dc3545'],
                legend: {
                    position: 'bottom'
                }
            };

            const chart = new ApexCharts(document.querySelector("#orderStatusChart"), options);
            chart.render();
        });
}

// 初始化每日銷售圖表
function initDailySalesChart() {
    const chartContainer = document.querySelector("#dailySalesChart");
    chartContainer.innerHTML = '<div class="text-center p-3">加載中...</div>';

    fetch('api/get_daily_sales.php')
        .then(response => response.json())
        .then(response => {
            console.log('每日銷售數據:', response);

            if (!response.success) {
                throw new Error(response.message || '獲取數據失敗');
            }

            if (!response.data || response.data.length === 0) {
                chartContainer.innerHTML = '<div class="text-center p-4">暫無銷售數據</div>';
                return;
            }

            const data = response.data;
            const options = {
                series: [{
                    name: '銷售金額',
                    data: data.map(item => item.total_sales)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '70%',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: data.map(item => item.date),
                    labels: {
                        rotate: -45,
                        rotateAlways: false
                    },
                    title: {
                        text: '日期'
                    }
                },
                yaxis: {
                    title: {
                        text: '銷售金額 ($)'
                    },
                    labels: {
                        formatter: function(val) {
                            return '$' + val.toFixed(2);
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return '$' + val.toFixed(2);
                        }
                    }
                },
                colors: ['#0062ff'],
                title: {
                    text: '最近30天銷售金額統計',
                    align: 'left',
                    style: {
                        fontSize: '16px'
                    }
                }
            };

            chartContainer.innerHTML = '';

            try {
                const chart = new ApexCharts(chartContainer, options);
                chart.render();
            } catch (error) {
                console.error('圖表渲染錯誤:', error);
                chartContainer.innerHTML = '<div class="text-center p-4">圖表加載失敗</div>';
            }
        })
        .catch(error => {
            console.error('數據加載錯誤:', error);
            chartContainer.innerHTML = `<div class="text-center p-4">數據加載失敗: ${error.message}</div>`;
        });
}

// 加載庫存預警
function loadStockAlerts() {
    fetch('api/get_stock_alerts.php')
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                throw new Error(response.message || '獲取數據失敗');
            }

            const { stockOut, lowStock } = response.data;

            // 更新缺貨預警
            const stockOutHtml = stockOut.length ? stockOut.map(product => `
                <div class="alert alert-danger d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>${product.name}</strong>
                        <div class="small">價格: $${product.price}</div>
                    </div>
                    <span class="badge bg-danger">缺貨</span>
                </div>
            `).join('') : '<div class="text-center text-muted py-3">暫無缺貨產品</div>';

            document.getElementById('stockOutAlert').innerHTML = stockOutHtml;

            // 更新低庫存預警
            const lowStockHtml = lowStock.length ? lowStock.map(product => `
                <div class="alert alert-warning d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>${product.name}</strong>
                        <div class="small">價格: $${product.price}</div>
                    </div>
                    <span class="badge bg-warning text-dark">剩餘 ${product.stock} 件</span>
                </div>
            `).join('') : '<div class="text-center text-muted py-3">暫無低庫存產品</div>';

            document.getElementById('lowStockAlert').innerHTML = lowStockHtml;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('stockOutAlert').innerHTML = 
                '<div class="text-center text-danger">加載失敗</div>';
            document.getElementById('lowStockAlert').innerHTML = 
                '<div class="text-center text-danger">加載失敗</div>';
        });
}

// 頁面加載時初始化
document.addEventListener('DOMContentLoaded', () => {
    loadStats();
    initProductSalesChart();
    initOrderStatusChart();
    initDailySalesChart();
    loadStockAlerts();
}); 