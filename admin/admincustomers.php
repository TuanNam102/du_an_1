<?php
// 1. GỌI FILE BẢO VỆ
include 'check_admin.php'; 

// 2. KẾT NỐI CSDL
include '../config/db_connect.php';

// 3. TRUY VẤN TẤT CẢ NGƯỜI DÙNG
$sql_customers = "
    SELECT 
        ma_nguoi_dung, ho_ten, email, so_dien_thoai, vai_tro, trang_thai 
    FROM 
        nguoi_dung 
    ORDER BY 
        vai_tro ASC, ho_ten ASC
";
$result_customers = mysqli_query($conn, $sql_customers);
$customers = mysqli_fetch_all($result_customers, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>SAPHIRA - Quản lý Người dùng</title>
    <link rel="stylesheet" href="../public/css/style-admin.css" />
</head>
<body>
    <aside class="admin-sidebar" id="admin-sidebar">
         <div class="sidebar-header">
            <h2 class="sidebar-logo">SAPHIRA</h2>
        </div>
        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <li class="menu-item active">
                    <a href="">
                        <span class="material-symbols-outlined"></span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="adminproducts.php">
                        <span class="material-symbols-outlined"></span>
                        <span class="menu-text">Quản lý sản phẩm</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admindonhang.php">
                        <span class="material-symbols-outlined"></span>
                        <span class="menu-text">Quản lý đơn hàng</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admincustomers.php">
                        <span class="material-symbols-outlined"></span>
                        <span class="menu-text">Quản lý khách hàng</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admincategories.php">
                        <span class="material-symbols-outlined"></span>
                        <span class="menu-text">Quản lý danh mục</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="brands.php">
                        <span class="material-symbols-outlined"></span>
                        <span class="menu-text">Quản lý thương hiệu</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="settings.php">
                        <span class="material-symbols-outlined"></span>
                        <span class="menu-text">Cài đặt</span>
                    </a>
                </li>
                <li class="menu-item menu-logout">
                    <a href="main.php?r=logout">
                        <span class="material-symbols-outlined"></span>
                        <span class="menu-text">Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    <div class="admin-main-content">
        <header class="admin-topbar">
            <!-- Giữ nguyên -->
        </header>
        <main class="admin-main-area">
            <div class="admin-container">
                <div class="mb-8">
                    <h1 class="page-title">Quản lý Người dùng</h1>
                </div>
                <div class="filter-bar-admin">
                    <div class="action-group-admin">
                        <a href="customer_add.php" class="btn-primary-admin" style="text-decoration: none;">
                            <span class="material-symbols-outlined">add</span>
                            <span>Thêm Người dùng Mới</span>
                        </a>
                    </div>
                </div>
                <div class="table-card-admin">
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID (Email)</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Vai trò</th>
                                    <th>Trạng thái</th>
                                    <th class="col-text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($customers)): ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 20px;">
                                            Chưa có người dùng nào.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($customers as $customer): 
                                        $role_text = ($customer['vai_tro'] == 'quan_tri') ? 'Admin' : 'Khách hàng';
                                        $status_text = ($customer['trang_thai'] == 1) ? 'Hoạt động' : 'Khóa';
                                        $status_class = ($customer['trang_thai'] == 1) ? 'status-delivered' : 'status-cancelled';
                                    ?>
                                        <tr>
                                            <td>#US<?php echo htmlspecialchars($customer['ma_nguoi_dung']); ?> (<?php echo htmlspecialchars($customer['email']); ?>)</td>
                                            <td class="col-text-light"><?php echo htmlspecialchars($customer['ho_ten']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['so_dien_thoai'] ?? 'N/A'); ?></td>
                                            <td><?php echo $role_text; ?></td>
                                            <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                            <td class="col-text-center">
                                                <div class="table-actions">
                                                    <a href="customer_edit.php?id=<?php echo $customer['ma_nguoi_dung']; ?>" class="action-btn-edit" title="Sửa">
                                                        <span class="material-symbols-outlined">edit</span>
                                                    </a>
                                                    <a href="customer_delete.php?id=<?php echo $customer['ma_nguoi_dung']; ?>" class="action-btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn XÓA VĨNH VIỄN người dùng này?');">
                                                        <span class="material-symbols-outlined">delete</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <footer class="admin-footer">
                <p>© SAPHIRA 2025 – Mọi quyền được bảo lưu.</p>
            </footer>
        </main>
    </div>
</body>
</html>
