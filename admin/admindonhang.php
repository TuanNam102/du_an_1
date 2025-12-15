<?php
// 1. GỌI FILE BẢO VỆ
include 'check_admin.php'; 

// 2. KẾT NỐI CSDL
include '../config/db_connect.php';

// 3. LOGIC TRUY VẤN TẤT CẢ ĐƠN HÀNG
$sql_orders = "
    SELECT 
        dh.ma_don_hang, 
        nd.ho_ten, 
        dh.thoi_gian_dat, 
        dh.tong_tien, 
        dh.trang_thai_don
    FROM 
        don_hang dh
    JOIN 
        nguoi_dung nd ON dh.ma_nguoi_dung = nd.ma_nguoi_dung
    ORDER BY 
        dh.thoi_gian_dat DESC
";
$result_orders = mysqli_query($conn, $sql_orders);
$orders = mysqli_fetch_all($result_orders, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Quản lý Đơn hàng</title>
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
                    <h1 class="page-title">Quản lý Đơn hàng</h1>
                </div>
                <div class="filter-bar-admin"></div>
                <div class="table-card-admin">
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th class="col-text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 20px;">
                                            Chưa có đơn hàng nào.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $order): 
                                        $status_text = 'Chờ xử lý';
                                        $status_class = 'status-pending';
                                        if ($order['trang_thai_don'] == 'da_giao') {
                                            $status_text = 'Đã giao';
                                            $status_class = 'status-delivered';
                                        } elseif ($order['trang_thai_don'] == 'dang_giao') {
                                            $status_text = 'Đang giao';
                                            $status_class = 'status-shipped';
                                        } elseif ($order['trang_thai_don'] == 'da_huy') {
                                            $status_text = 'Đã hủy';
                                            $status_class = 'status-cancelled';
                                        }
                                    ?>
                                        <tr>
                                            <td class="col-text-light">#DON-<?php echo htmlspecialchars($order['ma_don_hang']); ?></td>
                                            <td><?php echo htmlspecialchars($order['ho_ten']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($order['thoi_gian_dat'])); ?></td>
                                            <td class="col-text-light"><?php echo number_format($order['tong_tien']); ?>đ</td>
                                            <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                            <td class="col-text-center">
                                                <div class="table-actions">
                                                    <button class="action-btn-view" title="Xem Chi tiết">
                                                        <span class="material-symbols-outlined">visibility</span>
                                                    </button>
                                                    <button class="action-btn-update" title="Cập nhật Trạng thái">
                                                        <span class="material-symbols-outlined">update</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container"></div>
                </div>
            </div>
            <footer class="admin-footer">
                <p>© SAPHIRA 2025 – Mọi quyền được bảo lưu.</p>
            </footer>
        </main>
    </div>
</body>
</html>
