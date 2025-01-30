<div class="col-md-3 col-lg-2 px-0 sidebar">
    <div class="d-flex flex-column p-3">
        <h4 class="text-center mb-4">用戶登錄</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>" href="admin_dashboard.php">
                    <i class="fas fa-home me-2"></i>首頁
                </a>
            </li>
            <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'user_management.php' ? 'active' : ''; ?>" href="user_management.php">
                    <i class="fas fa-users me-2"></i>員工管理
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'customer_management.php' ? 'active' : ''; ?>" href="customer_management.php">
                    <i class="fas fa-user-friends me-2"></i>客戶管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'order_management.php' ? 'active' : ''; ?>" href="order_management.php">
                    <i class="fas fa-shopping-cart me-2"></i>訂單管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'product_management.php' ? 'active' : ''; ?>" href="product_management.php">
                    <i class="fas fa-box me-2"></i>產品管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>登出
                </a>
            </li>
        </ul>
    </div>
</div> 