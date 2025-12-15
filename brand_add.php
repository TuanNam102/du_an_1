<?php
// brand_add.php
include 'check_admin.php';
include '../config/db_connect.php';

$message = '';
$message_type = '';

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_thuong_hieu = trim($_POST['ten_thuong_hieu']);
    $mo_ta = trim($_POST['mo_ta']);
    $logo = '';

    // Xử lý upload logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['logo']['tmp_name'];
        $file_name = $_FILES['logo']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed)) {
            if ($_FILES['logo']['size'] <= 2 * 1024 * 1024) { // Giới hạn 2MB
                $new_filename = 'brand_' . time() . '_' . uniqid() . '.' . $file_ext;
                $upload_dir = '../public/uploads/brands/';
                
                // Tạo thư mục nếu chưa tồn tại
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $destination = $upload_dir . $new_filename;
                if (move_uploaded_file($file_tmp, $destination)) {
                    $logo = '/public/uploads/brands/' . $new_filename;
                } else {
                    $message = 'Lỗi khi lưu file logo.';
                    $message_type = 'error';
                }
            } else {
                $message = 'File logo quá lớn (tối đa 2MB).';
                $message_type = 'error';
            }
        } else {
            $message = 'Chỉ chấp nhận file: JPG, PNG, GIF, WebP.';
            $message_type = 'error';
        }
    }

    // Nếu không có lỗi upload → lưu vào CSDL
    if ($message === '') {
        $sql = "INSERT INTO thuong_hieu (ten_thuong_hieu, mo_ta, logo) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $ten_thuong_hieu, $mo_ta, $logo);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = 'Thêm thương hiệu thành công!';
            $message_type = 'success';
            // Reset form sau khi thêm thành công
            $_POST = [];
        } else {
            $message = 'Lỗi khi thêm thương hiệu. Vui lòng thử lại.';
            $message_type = 'error';
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Thêm Thương hiệu Mới</title>
    
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <link rel="stylesheet" href="../public/css/style-admin.css" />
    <style>
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333; }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #6b46c1;
            box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
        }
        textarea.form-control { min-height: 120px; resize: vertical; }
        .preview-logo {
            margin-top: 10px;
            max-width: 150px;
            max-height: 150px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid #eee;
            display: none;
        }
        .message {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        .message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
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
                <div class="mb-8">
                    <h1 class="page-title">Thêm Thương hiệu Mới</h1>
                </div>

                <?php if ($message): ?>
                    <div class="message <?php echo $message_type; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div class="table-card-admin">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="p-8">
                            <div class="form-group">
                                <label class="form-label">Tên thương hiệu <span style="color:red;">*</span></label>
                                <input type="text" name="ten_thuong_hieu" class="form-control" required 
                                       value="<?php echo isset($_POST['ten_thuong_hieu']) ? htmlspecialchars($_POST['ten_thuong_hieu']) : ''; ?>" 
                                       placeholder="Ví dụ: Chanel, Dior, Gucci...">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mô tả thương hiệu</label>
                                <textarea name="mo_ta" class="form-control" placeholder="Giới thiệu ngắn về thương hiệu (không bắt buộc)..."><?php echo isset($_POST['mo_ta']) ? htmlspecialchars($_POST['mo_ta']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Logo thương hiệu</label>
                                <input type="file" name="logo" accept="image/*" class="form-control" onchange="previewImage(this);">
                                <p style="color:#666; font-size:0.9rem; margin-top:0.5rem;">
                                    Định dạng: JPG, PNG, GIF, WebP • Tối đa 2MB
                                </p>
                                <img id="logo-preview" class="preview-logo" src="" alt="Preview logo">
                            </div>

                            <div class="action-group-admin" style="margin-top: 2rem; gap: 1rem;">
                                <button type="submit" class="btn-primary-admin">
                                    <span class="material-symbols-outlined">check</span>
                                    <span>Thêm Thương hiệu</span>
                                </button>
                                <a href="brands.php" class="btn-secondary-admin">
                                    <span class="material-symbols-outlined">arrow_back</span>
                                    <span>Quay lại danh sách</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <footer class="admin-footer">
                <p>© SAPHIRA 2025 – Mọi quyền được bảo lưu.</p>
            </footer>
        </main>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('logo-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
    <script src="../public/js/script-admin.js"></script>
</body>
</html>