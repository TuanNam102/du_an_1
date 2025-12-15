<?php
// 1. GỌI FILE BẢO VỆ
include 'check_admin.php'; 

// 2. KẾT NỐI CSDL
include '../config/db_connect.php';

// 3. LOGIC TRUY VẤN TẤT CẢ DANH MỤC VÀ ĐẾM SẢN PHẨM
$sql_categories = "
    SELECT 
        dm.ma_danh_muc,
        dm.ten_danh_muc,
        dm.duong_dan,
        COUNT(nh.ma_nuoc_hoa) AS SoLuongSanPham
    FROM 
        danh_muc dm
    LEFT JOIN 
        nuoc_hoa nh ON dm.ma_danh_muc = nh.ma_danh_muc
    GROUP BY 
        dm.ma_danh_muc, dm.ten_danh_muc
    ORDER BY 
        dm.ma_danh_muc ASC
";
$result_categories = mysqli_query($conn, $sql_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Quản lý Danh mục</title>
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
                    <a href="admin.php">
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="adminproducts.php">
                        <span class="menu-text">Quản lý sản phẩm</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admindonhang.php">
                        <span class="menu-text">Quản lý đơn hàng</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admincustomers.php">
                        <span class="menu-text">Quản lý khách hàng</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admincategories.php">
                        <span class="menu-text">Quản lý danh mục</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="brands.php">
                        <span class="menu-text">Quản lý thương hiệu</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="settings.php">
                        <span class="menu-text">Cài đặt</span>
                    </a>
                </li>
                <li class="menu-item menu-logout">
                    <a href="main.php?r=logout">
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
                    <h1 class="page-title">Quản lý Danh mục</h1>
                </div>
                <div class="filter-bar-admin">
                    <div class="action-group-admin">
                        <a href="category_add.php" class="btn-primary-admin" style="text-decoration: none;">
                            <span class="material-symbols-outlined">add</span>
                            <span>Thêm Danh mục Mới</span>
                        </a>
                    </div>
                </div>
                <div class="table-card-admin">
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID Danh mục</th>
                                    <th>Tên Danh mục</th>
                                    <th>Mô tả</th>
                                    <th>Số lượng sản phẩm</th>
                                    <th class="col-text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 20px;">
                                            Chưa có danh mục nào.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td>#CAT<?php echo htmlspecialchars($category['ma_danh_muc']); ?></td>
                                            <td class="col-text-light"><?php echo htmlspecialchars($category['ten_danh_muc']); ?></td>
                                            <td><?php echo htmlspecialchars($category['mo_ta'] ?? '...'); ?></td>
                                            <td><?php echo $category['SoLuongSanPham']; ?></td>
                                            <td class="col-text-center">
                                                <div class="table-actions">
                                                    <a href="category_edit.php?id=<?php echo $category['ma_danh_muc']; ?>" class="action-btn-edit" title="Sửa">
                                                        <span class="material-symbols-outlined">edit</span>
                                                    </a>
                                                    <a href="category_delete.php?id=<?php echo $category['ma_danh_muc']; ?>" class="action-btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Hành động này không thể hoàn tác!');">
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
</body>
</html>
