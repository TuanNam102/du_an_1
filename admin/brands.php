<?php
// brands.php – ĐÃ SỬA: CỘT SỬA/XÓA LUÔN HIỂN THỊ RÕ RÀNG
include 'check_admin.php'; 
include '../config/db_connect.php';

$sql_brands = "
    SELECT 
        th.ma_thuong_hieu,
        th.ten_thuong_hieu,
        th.logo,
        th.mo_ta,
        COUNT(nh.ma_nuoc_hoa) as so_san_pham
    FROM thuong_hieu th
    LEFT JOIN nuoc_hoa nh ON th.ma_thuong_hieu = nh.ma_thuong_hieu
    GROUP BY th.ma_thuong_hieu
    ORDER BY th.ten_thuong_hieu ASC
";
$result_brands = mysqli_query($conn, $sql_brands);
$brands = mysqli_fetch_all($result_brands, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Quản lý Thương hiệu</title>
    
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../public/css/style-admin.css" />

    <style>
        /* Logo luôn gọn trong khung */
        .brand-logo-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            background: #222;
            padding: 8px;
            border-radius: 10px;
            border: 1px solid #444;
            display: block;
            margin: 0 auto;
        }
        .no-logo-placeholder {
            width: 60px; height: 60px;
            background: #333; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto; border: 1px dashed #666;
        }
        .no-logo-placeholder .material-symbols-outlined { font-size: 28px; color: #888; }

        /* CỘT HÀNH ĐỘNG – luôn hiển thị đẹp */
        .table-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            align-items: center;
        }
        .action-btn-edit,
        .action-btn-delete {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .action-btn-edit {
            background: #3498db;
            color: white;
        }
        .action-btn-edit:hover { background: #2980b9; transform: scale(1.1); }
        .action-btn-delete {
            background: #e74c3c;
            color: white;
        }
        .action-btn-delete:hover { background: #c0392b; transform: scale(1.1); }
        .action-btn-edit .material-symbols-outlined,
        .action-btn-delete .material-symbols-outlined {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar giữ nguyên -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="sidebar-header"><h2 class="sidebar-logo">SAPHIRA</h2></div>
        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <li class="menu-item"><a href="admin.php"><span class="material-symbols-outlined">dashboard</span><span class="menu-text">Dashboard</span></a></li>
                <li class="menu-item"><a href="adminproducts.php"><span class="material-symbols-outlined">inventory_2</span><span class="menu-text">Quản lý sản phẩm</span></a></li>
                <li class="menu-item"><a href="admindonhang.php"><span class="material-symbols-outlined">shopping_cart</span><span class="menu-text">Quản lý đơn hàng</span></a></li>
                <li class="menu-item"><a href="admincustomers.php"><span class="material-symbols-outlined">group</span><span class="menu-text">Quản lý khách hàng</span></a></li>
                <li class="menu-item"><a href="admincategories.php"><span class="material-symbols-outlined">category</span><span class="menu-text">Quản lý danh mục</span></a></li>
                <li class="menu-item active">
                    <a href="brands.php"><span class="material-symbols-outlined">branding_watermark</span><span class="menu-text">Quản lý thương hiệu</span></a>
                </li>
                <li class="menu-item"><a href="settings.php"><span class="material-symbols-outlined">settings</span><span class="menu-text">Cài đặt</span></a></li>
                <li class="menu-item menu-logout"><a href="logout.php"><span class="material-symbols-outlined">logout</span><span class="menu-text">Đăng xuất</span></a></li>
            </ul>
        </nav>
    </aside>

    <div class="admin-main-content">
        <header class="admin-topbar"></header>

        <main class="admin-main-area">
            <div class="admin-container">
                <div class="mb-8">
                    <h1 class="page-title">Quản lý Thương hiệu</h1>
                </div>

                <div class="filter-bar-admin">
                    <div class="action-group-admin">
                        <a href="brand_add.php" class="btn-primary-admin" style="text-decoration:none;">
                            <span class="material-symbols-outlined">add</span> Thêm Thương hiệu Mới
                        </a>
                    </div>
                </div>

                <div class="table-card-admin">
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th width="80">ID</th>
                                    <th width="100">Logo</th>
                                    <th>Tên thương hiệu</th>
                                    <th>Mô tả</th>
                                    <th width="120" class="col-text-center">Số sản phẩm</th>
                                    <th width="130" class="col-text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($brands)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center; padding:50px; color:#888;">
                                            <span class="material-symbols-outlined" style="font-size:60px; opacity:0.3;">branding_watermark</span><br><br>
                                            Chưa có thương hiệu nào.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($brands as $brand): ?>
                                        <tr>
                                            <td><strong>#TH<?php echo htmlspecialchars($brand['ma_thuong_hieu']); ?></strong></td>

                                            <td>
                                                <?php if (!empty($brand['logo'])): ?>
                                                    <img src="<?php echo htmlspecialchars($brand['logo']); ?>" 
                                                         alt="<?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>" 
                                                         class="brand-logo-img">
                                                <?php else: ?>
                                                    <div class="no-logo-placeholder">
                                                        <span class="material-symbols-outlined">image</span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>

                                            <td class="col-text-light">
                                                <strong><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></strong>
                                            </td>

                                            <td style="max-width:320px; color:#aaa;">
                                                <?php 
                                                    $mota = $brand['mo_ta'] ?? '';
                                                    echo $mota 
                                                        ? htmlspecialchars(mb_substr($mota, 0, 100)) . (mb_strlen($mota) > 100 ? '...' : '')
                                                        : '<em style="color:#666;">Chưa có mô tả</em>';
                                                ?>
                                            </td>

                                            <td class="col-text-center">
                                                <strong style="color:#f39c12;"><?php echo $brand['so_san_pham']; ?></strong>
                                            </td>

                                            <!-- CỘT HÀNH ĐỘNG – RÕ RÀNG, ĐẸP MẮT -->
                                            <td class="col-text-center">
                                                <div class="table-actions">
                                                    <a href="brand_edit.php?id=<?php echo $brand['ma_thuong_hieu']; ?>" 
                                                       class="action-btn-edit" title="Sửa thương hiệu">
                                                        <span class="material-symbols-outlined">edit</span>
                                                    </a>
                                                    <a href="brand_delete.php?id=<?php echo $brand['ma_thuong_hieu']; ?>" 
                                                       class="action-btn-delete" title="Xóa thương hiệu"
                                                       onclick="return confirm('XÓA thương hiệu «<?php echo addslashes(htmlspecialchars($brand['ten_thuong_hieu'])); ?>»?\n\nCác sản phẩm sẽ được chuyển về \"Không có thương hiệu\".')">
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

    <script src="../public/js/script-admin.js"></script>
    <Div>   </Div>
</body> 
</html> 