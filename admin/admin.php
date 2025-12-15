<?php
// 1. GỌI FILE BẢO VỆ
include 'check_admin.php'; 

// 2. KẾT NỐI CSDL
include __DIR__ . '/../config/db_connect.php';

// 3. LOGIC TRUY VẤN DỮ LIỆU DASHBOARD
$sql_total_orders = "SELECT COUNT(ma_don_hang) as total FROM don_hang";
$result_total_orders = mysqli_query($conn, $sql_total_orders);
$total_orders = mysqli_fetch_assoc($result_total_orders)['total'];

$sql_total_revenue = "SELECT IFNULL(SUM(tong_tien),0) as total FROM don_hang WHERE trang_thai_don = 'da_giao'";
$result_total_revenue = mysqli_query($conn, $sql_total_revenue);
$total_revenue = mysqli_fetch_assoc($result_total_revenue)['total'];

$sql_total_customers = "SELECT COUNT(ma_nguoi_dung) as total FROM nguoi_dung WHERE vai_tro = 'khach_hang'";
$result_total_customers = mysqli_query($conn, $sql_total_customers);
$total_customers = mysqli_fetch_assoc($result_total_customers)['total'];

$sql_total_products = "SELECT COUNT(ma_nuoc_hoa) as total FROM nuoc_hoa";
$result_total_products = mysqli_query($conn, $sql_total_products);
$total_products = mysqli_fetch_assoc($result_total_products)['total'];

$sql_recent_orders = "
    SELECT dh.ma_don_hang, nd.ho_ten, dh.thoi_gian_dat, dh.tong_tien, dh.trang_thai_don
    FROM don_hang dh
    JOIN nguoi_dung nd ON dh.ma_nguoi_dung = nd.ma_nguoi_dung
    ORDER BY dh.thoi_gian_dat DESC
    LIMIT 5
";
$result_recent_orders = mysqli_query($conn, $sql_recent_orders);
$recent_orders = mysqli_fetch_all($result_recent_orders, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Bảng điều khiển quản trị</title>
    
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

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
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="adminproducts.php">
                        <span class="material-symbols-outlined">inventory_2</span>
                        <span class="menu-text">Quản lý sản phẩm</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admindonhang.php">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        <span class="menu-text">Quản lý đơn hàng</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admincustomers.php">
                        <span class="material-symbols-outlined">group</span>
                        <span class="menu-text">Quản lý khách hàng</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admincategories.php">
                        <span class="material-symbols-outlined">category</span>
                        <span class="menu-text">Quản lý danh mục</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="brands.php">
                        <span class="material-symbols-outlined">branding_watermark</span>
                        <span class="menu-text">Quản lý thương hiệu</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="settings.php">
                        <span class="material-symbols-outlined">settings</span>
                        <span class="menu-text">Cài đặt</span>
                    </a>
                </li>
                <li class="menu-item menu-logout">
                    <a href="main.php?r=logout">
                        <span class="material-symbols-outlined">logout</span>
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
                    <h1 class="page-title">Dashboard</h1>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <p class="stat-title">Doanh thu</p>
                        <p class="stat-value"><?php echo number_format($total_revenue ?? 0); ?>đ</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-title">Tổng đơn hàng</p>
                        <p class="stat-value"><?php echo $total_orders; ?></p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-title">Số khách hàng</p>
                        <p class="stat-value"><?php echo $total_customers; ?></p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-title">Số sản phẩm</p>
                        <p class="stat-value"><?php echo $total_products; ?></p>
                    </div>
                </div>
                <div class="recent-orders-card">
                    <h3 class="card-title">Đơn hàng gần đây</h3>
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                    <?php
                                        $status_text = 'Chờ xử lý';
                                        $status_class = 'status-processing';
                                        if ($order['trang_thai_don'] == 'da_giao') {
                                            $status_text = 'Hoàn thành';
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
                                        <td>#DON-<?php echo htmlspecialchars($order['ma_don_hang']); ?></td>
                                        <td><?php echo htmlspecialchars($order['ho_ten']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($order['thoi_gian_dat'])); ?></td>
                                        <td><?php echo number_format($order['tong_tien']); ?>đ</td>
                                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
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
