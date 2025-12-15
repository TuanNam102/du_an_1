<?php
// 1. GỌI FILE BẢO VỆ
include 'check_admin.php'; 

// 2. KẾT NỐI CSDL
include '../config/db_connect.php';
include '../config/helpers.php';

// 3. LOGIC TRUY VẤN TẤT CẢ SẢN PHẨM
$sql_products = "
    SELECT 
        nh.ma_nuoc_hoa,
        nh.ten_nuoc_hoa,
        MIN(h.duong_dan_hinh) AS hinh_anh,
        dm.ten_danh_muc,
        MIN(bt.gia_niem_yet) AS GiaThap,
        COALESCE(SUM(bt.so_luong_ton), 0) AS TongTonKho
    FROM nuoc_hoa nh
    LEFT JOIN danh_muc dm ON nh.ma_danh_muc = dm.ma_danh_muc
    LEFT JOIN bien_the_nuoc_hoa bt ON nh.ma_nuoc_hoa = bt.ma_nuoc_hoa
    LEFT JOIN hinh_anh_nuoc_hoa h ON nh.ma_nuoc_hoa = h.ma_nuoc_hoa AND (h.loai_hinh_anh = 'anh_lon' OR h.loai_hinh_anh IS NULL)
    GROUP BY nh.ma_nuoc_hoa
    ORDER BY nh.ma_nuoc_hoa ASC
";
$result_products = mysqli_query($conn, $sql_products);
$products = mysqli_fetch_all($result_products, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Quản lý Sản phẩm</title>
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
                    <h1 class="page-title">Quản lý Sản phẩm</h1>
                </div>
                <div class="filter-bar-admin">
                    <div class="action-group-admin">
                        <a href="product_add.php" class="btn-primary-admin" style="text-decoration: none;">
                            <span class="material-symbols-outlined">add</span>
                            <span>Thêm Sản phẩm Mới</span>
                        </a>
                    </div>
                </div>
                <div class="table-card-admin">
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá (thấp nhất)</th>
                                    <th>Trạng thái</th>
                                    <th>Tồn kho</th>
                                    <th class="col-text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 20px;">
                                            Chưa có sản phẩm nào.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $product): 
                                        $status_text = ($product['TongTonKho'] > 0) ? 'Còn hàng' : 'Hết hàng';
                                        $status_class = ($product['TongTonKho'] > 0) ? 'status-delivered' : 'status-cancelled';
                                    ?>
                                        <tr>
                                            <td>#SP<?php echo htmlspecialchars($product['ma_nuoc_hoa']); ?></td>
                                            <td>
                                                <img alt="<?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?>" class="table-product-img" src="<?php echo htmlspecialchars(asset($product['hinh_anh'] ?? 'img/placeholder.png')); ?>" />
                                            </td>
                                            <td class="col-text-light"><?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?></td>
                                            <td><?php echo htmlspecialchars($product['ten_danh_muc'] ?? 'N/A'); ?></td>
                                            <td class="col-text-light"><?php echo number_format($product['GiaThap'] ?? 0); ?>đ</td>
                                            <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                            <td><?php echo $product['TongTonKho']; ?></td>
                                            <td class="col-text-center">
                                                <div class="table-actions">
                                                    <a href="product_edit.php?id=<?php echo $product['ma_nuoc_hoa']; ?>" class="action-btn-edit" title="Sửa">
                                                        <span class="material-symbols-outlined">edit</span>
                                                    </a>
                                                    <a href="product_delete.php?id=<?php echo $product['ma_nuoc_hoa']; ?>" class="action-btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn XÓA VĨNH VIỄN sản phẩm này?');">
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
                    <div class="pagination-container"></div>
                </div>
            </div>
            <footer class="admin-footer">
                <p>© SAPHIRA 2025 – Mọi quyền được bảo lưu.</p>
            </footer>
        </main>
    </div>
    <script src="../public/js/script-admin.js"></script>
</body>
</html>
