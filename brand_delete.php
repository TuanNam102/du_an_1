<?php
// brand_delete.php
include 'check_admin.php';
include '../config/db_connect.php';

$message = '';
$message_type = '';
$redirect = 'brands.php';

// Kiểm tra ID hợp lệ
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $message = 'ID thương hiệu không hợp lệ!';
    $message_type = 'error';
} else {
    $brand_id = (int)$_GET['id'];

    // Bắt đầu transaction để đảm bảo toàn vẹn dữ liệu
    mysqli_autocommit($conn, false);
    $success = true;

    try {
        // 1. Lấy thông tin logo để xóa file vật lý (nếu có)
        $sql_logo = "SELECT logo FROM thuong_hieu WHERE ma_thuong_hieu = ?";
        $stmt_logo = mysqli_prepare($conn, $sql_logo);
        mysqli_stmt_bind_param($stmt_logo, "i", $brand_id);
        mysqli_stmt_execute($stmt_logo);
        $result_logo = mysqli_stmt_get_result($stmt_logo);
        $brand = mysqli_fetch_assoc($result_logo);

        // 2. Kiểm tra xem có sản phẩm nào đang dùng thương hiệu này không
        $sql_check = "SELECT COUNT(*) as total FROM nuoc_hoa WHERE ma_thuong_hieu = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "i", $brand_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        $count = mysqli_fetch_assoc($result_check)['total'];

        if ($count > 0) {
            // Có sản phẩm → chuyển về NULL (không thuộc thương hiệu nào)
            $sql_update = "UPDATE nuoc_hoa SET ma_thuong_hieu = NULL WHERE ma_thuong_hieu = ?";
            $stmt_update = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "i", $brand_id);
            if (!mysqli_stmt_execute($stmt_update)) {
                $success = false;
            }
            mysqli_stmt_close($stmt_update);
        }

        // 3. Xóa thương hiệu khỏi CSDL
        if ($success) {
            $sql_delete = "DELETE FROM thuong_hieu WHERE ma_thuong_hieu = ?";
            $stmt_delete = mysqli_prepare($conn, $sql_delete);
            mysqli_stmt_bind_param($stmt_delete, "i", $brand_id);
            if (!mysqli_stmt_execute($stmt_delete)) {
                $success = false;
            }
            mysqli_stmt_close($stmt_delete);
        }

        // 4. Nếu xóa thành công → xóa file logo cũ
        if ($success && !empty($brand['logo'])) {
            $logo_path = '../' . ltrim($brand['logo'], '/');
            if (file_exists($logo_path)) {
                @unlink($logo_path);
            }
        }

        // Commit hoặc rollback
        if ($success) {
            mysqli_commit($conn);
            $message = 'Xóa thương hiệu thành công!';
            $message_type = 'success';
        } else {
            mysqli_rollback($conn);
            $message = 'Lỗi khi xóa thương hiệu. Vui lòng thử lại.';
            $message_type = 'error';
        }

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $message = 'Đã xảy ra lỗi hệ thống.';
        $message_type = 'error';
    }

    mysqli_autocommit($conn, true);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Xóa Thương hiệu</title>
    
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../public/css/style-admin.css" />
    <style>
        .message {
            padding: 1.5rem 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            text-align: center;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .message.success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .message.error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        .icon-large {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-logo">SAPHIRA</h2>
        </div>
        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <li class="menu-item"><a href="admin.php"><span class="material-symbols-outlined">dashboard</span><span class="menu-text">Dashboard</span></a></li>
                <li class="menu-item"><a href="adminproducts.php"><span class="material-symbols-outlined">inventory_2</span><span class="menu-text">Quản lý sản phẩm</span></a></li>
                <li class="menu-item"><a href="admindonhang.php"><span class="material-symbols-outlined">shopping_cart</span><span class="menu-text">Quản lý đơn hàng</span></a></li>
                <li class="menu-item"><a href="admincustomers.php"><span class="material-symbols-outlined">group</span><span class="menu-text">Quản lý khách hàng</span></a></li>
                <li class="menu-item"><a href="admincategories.php"><span class="material-symbols-outlined">category</span><span class="menu-text">Quản lý danh mục</span></a></li>
                <li class="menu-item active"><a href="brands.php"><span class="material-symbols-outlined">branding_watermark</span><span class="menu-text">Quản lý thương hiệu</span></a></li>
                <li class="menu-item"><a href="settings.php"><span class="material-symbols-outlined">settings</span><span class="menu-text">Cài đặt</span></a></li>
                <li class="menu-item menu-logout"><a href="logout.php"><span class="material-symbols-outlined">logout</span><span class="menu-text">Đăng xuất</span></a></li>
            </ul>
        </nav>
    </aside>

    <div class="admin-main-content">
        <header class="admin-topbar"></header>

        <main class="admin-main-area">
            <div class="admin-container">
                <div class="mb-8 text-center">
                    <h1 class="page-title">
                        <?php echo $message_type === 'success' ? 'Hoàn tất!' : 'Xóa thương hiệu'; ?>
                    </h1>
                </div>

                <div class="table-card-admin">
                    <div class="p-8 text-center">
                        <?php if ($message_type === 'success'): ?>
                            <span class="material-symbols-outlined icon-large" style="color:#28a745;">check_circle</span>
                            <div class="message success">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php else: ?>
                            <span class="material-symbols-outlined icon-large" style="color:#dc3545;">error</span>
                            <div class="message error">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <div class="action-group-admin" style="justify-content:center; margin-top:2rem;">
                            <a href="brands.php" class="btn-primary-admin">
                                <span class="material-symbols-outlined">arrow_back</span>
                                <span>Quay lại danh sách thương hiệu</span>
                            </a>
                        </div>

                        <p style="color:#666; margin-top: 2rem; font-size:0.95rem;">
                            Trang sẽ tự động chuyển hướng sau <span id="countdown">5</span> giây...
                        </p>
                    </div>
                </div>
            </div>

            <footer class="admin-footer">
                <p>© SAPHIRA 2025 – Mọi quyền được bảo lưu.</p>
            </footer>
        </main>
    </div>

    <script>
        // Tự động chuyển hướng sau 5 giây
        let seconds = 5;
        const countdown = document.getElementById('countdown');
        const timer = setInterval(() => {
            seconds--;
            countdown.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = '<?php echo $redirect; ?>';
            }
        }, 1000);
    </script>
    <script src="../public/js/script-admin.js"></script>
</body>
</html>