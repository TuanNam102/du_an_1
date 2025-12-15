<?php
// 1. GỌI FILE BẢO VỆ
include 'check_admin.php'; 

// 2. KẾT NỐI CSDL
include '../config/db_connect.php';

// Khởi tạo biến
$errors = [];
$ma_nd = 0;
$ho_ten = ''; $email = ''; $sdt = ''; 
$vai_tro = 'khach_hang'; $trang_thai = 1;

// 3. XỬ LÝ KHI FORM ĐƯỢC SUBMIT (POST) - LƯU THAY ĐỔI
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Lấy dữ liệu từ form
    $ma_nd = (int)$_POST['mand'];
    $ho_ten = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sdt = mysqli_real_escape_string($conn, $_POST['phone']);
    $vai_tro = ($_POST['quyentc'] == 1) ? 'quan_tri' : 'khach_hang';
    $trang_thai = (int)$_POST['trangthai'];
    
    // Validate
    if (empty($ho_ten)) $errors[] = 'Vui lòng nhập họ và tên.';
    if (empty($email)) $errors[] = 'Vui lòng nhập email.';

    // Kiểm tra trùng email (với người khác)
    $sql_check_email = "SELECT ma_nguoi_dung FROM nguoi_dung WHERE email = '$email' AND ma_nguoi_dung != $ma_nd";
    $result_check = mysqli_query($conn, $sql_check_email);
    if (mysqli_num_rows($result_check) > 0) {
        $errors[] = 'Email này đã được sử dụng bởi tài khoản khác.';
    }
    mysqli_free_result($result_check);

    // Xử lý mật khẩu (CHỈ THAY ĐỔI NẾU ĐƯỢC NHẬP)
    $mat_khau_sql = ""; // Chuỗi SQL mật khẩu
    if (!empty($_POST['password'])) {
        if ($_POST['password'] != $_POST['password_confirm']) {
            $errors[] = 'Mật khẩu xác nhận không khớp.';
        } else {
            // Nếu khớp, thêm vào câu lệnh UPDATE
            $mat_khau_hashed = md5($_POST['password']);
            $mat_khau_sql = ", mat_khau = '$mat_khau_hashed'"; // Thêm dấu phẩy ở đầu
        }
    }

    // Nếu không có lỗi, thực hiện UPDATE
    if (empty($errors)) {
        $sql_update = "
            UPDATE nguoi_dung SET
                ho_ten = '$ho_ten',
                email = '$email',
                so_dien_thoai = '$sdt',
                vai_tro = '$vai_tro',
                trang_thai = $trang_thai
                $mat_khau_sql 
            WHERE ma_nguoi_dung = $ma_nd
        ";
        
        if (mysqli_query($conn, $sql_update)) {
            header('Location: admincustomers.php?status=edit_success');
            exit();
        } else {
            $errors[] = 'Lỗi CSDL: ' . mysqli_error($conn);
        }
    }
} elseif (isset($_GET['id'])) {
    $ma_nd = (int)$_GET['id'];
    if ($ma_nd <= 0) {
        header('Location: admincustomers.php');
        exit();
    }
    $sql_get = "SELECT ho_ten, email, so_dien_thoai, vai_tro, trang_thai FROM nguoi_dung WHERE ma_nguoi_dung = $ma_nd";
    $result_get = mysqli_query($conn, $sql_get);
    if (mysqli_num_rows($result_get) == 1) {
        $user = mysqli_fetch_assoc($result_get);
        $ho_ten = $user['ho_ten'];
        $email = $user['email'];
        $sdt = $user['so_dien_thoai'];
        $vai_tro = $user['vai_tro'];
        $trang_thai = $user['trang_thai'];
    } else {
        header('Location: admincustomers.php');
        exit();
    }
} else {
    header('Location: admincustomers.php');
    exit();
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Sửa Người dùng</title>
    <link rel="stylesheet" href="../public/css/style-admin.css" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body>

    <aside class="admin-sidebar" id="admin-sidebar">
        <div>
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h2 class="sidebar-logo-text">SAPHIRA</h2>
            </div>
            <nav class="sidebar-nav">
                <h1 class="nav-section-title">ADMIN PANEL</h1>
                <a href="admin.php" class="sidebar-link">
                    <span class="material-symbols-outlined">dashboard</span>
                    <p>Dashboard</p>
                </a>
                <a href="adminproducts.php" class="sidebar-link">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <p>Quản lý Sản phẩm</p>
                </a>
                <a href="admindonhang.php" class="sidebar-link">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <p>Quản lý Đơn hàng</p>
                </a>
                <a href="admincustomers.php" class="sidebar-link active">
                    <span class="material-symbols-outlined">group</span>
                    <p>Quản lý Người dùng</p>
                </a>
                <a href="admincategories.php" class="sidebar-link">
                    <span class="material-symbols-outlined">category</span>
                    <p>Quản lý Danh mục</p>
                </a>
            </nav>
        </div>
        <div class="sidebar-footer">
            <a href="../main.php?r=logout" class="sidebar-link">
                <span class="material-symbols-outlined">logout</span>
                <p>Đăng xuất</p>
            </a>
        </div>
    </aside>

    <div class="admin-main-content">
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn" title="Mở menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h2 class="topbar-title">Bảng điều khiển quản trị</h2>
            </div>
            <div class="topbar-right">
                <span class="admin-username"><?php echo htmlspecialchars($admin_username); ?></span>
                <a href="../main.php?r=logout" class="logout-btn-desktop" title="Đăng xuất">
                    <span class="material-symbols-outlined">logout</span>
                </a>
            </div>
        </header>

        <main class="admin-main-area">
            <div class="admin-container">
                <div class="mb-8">
                    <h1 class="page-title">Sửa Người dùng: <?php echo htmlspecialchars($ho_ten); ?></h1>
                </div>

                <div class="form-card-admin">
                    <?php if (!empty($errors)): ?>
                        <div class="form-error-message">
                            <strong>Lỗi:</strong>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="customer_edit.php" method="POST">
                        <input type="hidden" name="mand" value="<?php echo $ma_nd; ?>">

                        <div class="form-group-admin">
                            <label for="fullname" class="form-label-admin">Họ và Tên</label>
                            <input type="text" id="fullname" name="fullname" class="form-input-admin" value="<?php echo htmlspecialchars($ho_ten); ?>" required>
                        </div>
                        <div class="form-group-admin">
                            <label for="email" class="form-label-admin">Email (Tên đăng nhập)</label>
                            <input type="email" id="email" name="email" class="form-input-admin" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="form-group-admin">
                            <label for="phone" class="form-label-admin">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" class="form-input-admin" value="<?php echo htmlspecialchars($sdt); ?>">
                        </div>
                        <div class="form-group-admin">
                            <label for="password" class="form-label-admin">Mật khẩu mới (Để trống nếu không đổi)</label>
                            <input type="password" id="password" name="password" class="form-input-admin">
                        </div>
                        <div class="form-group-admin">
                            <label for="password_confirm" class="form-label-admin">Xác nhận Mật khẩu mới</label>
                            <input type="password" id="password_confirm" name="password_confirm" class="form-input-admin">
                        </div>
                        <hr style="border-color: var(--color-border); margin: 20px 0;">
                        <div style="display: flex; gap: 20px;">
                            <div class="form-group-admin" style="flex: 1;">
                                <label for="quyentc" class="form-label-admin">Vai trò</label>
                                <select id="quyentc" name="quyentc" class="form-input-admin">
                                    <option value="2" <?php echo ($vai_tro == 'khach_hang') ? 'selected' : ''; ?>>Khách hàng</option>
                                    <option value="1" <?php echo ($vai_tro == 'quan_tri') ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>
                            <div class="form-group-admin" style="flex: 1;">
                                <label for="trangthai" class="form-label-admin">Trạng thái</label>
                                <select id="trangthai" name="trangthai" class="form-input-admin">
                                    <option value="1" <?php echo ($trang_thai == 1) ? 'selected' : ''; ?>>Hoạt động</option>
                                    <option value="0" <?php echo ($trang_thai == 0) ? 'selected' : ''; ?>>Khóa</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions-admin">
                            <button type="submit" class="btn-primary-admin">Lưu Thay Đổi</button>
                            <a href="admincustomers.php" class="btn-secondary-admin" style="text-decoration: none;">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
            <footer class="admin-footer">
                <p>© SAPHIRA 2025 – Mọi quyền được bảo lưu.</p>
            </footer>
        </main>
    </div>
</body>
</html>
