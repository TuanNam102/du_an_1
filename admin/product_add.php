<?php
// 1. GỌI FILE BẢO VỆ
include 'check_admin.php'; 

// 2. KẾT NỐI CSDL
include '../config/db_connect.php';

// Khởi tạo biến
$errors = [];

// Lấy danh mục và thương hiệu cho form (giữ nguyên)
$sql_categories = "SELECT ma_danh_muc, ten_danh_muc FROM danh_muc ORDER BY ten_danh_muc ASC";
$result_categories = mysqli_query($conn, $sql_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

$sql_brands = "SELECT ma_thuong_hieu, ten_thuong_hieu FROM thuong_hieu ORDER BY ten_thuong_hieu ASC";
$result_brands = mysqli_query($conn, $sql_brands);
$brands = mysqli_fetch_all($result_brands, MYSQLI_ASSOC);

// Dung tích: Vì SQL không có bảng dung_tich riêng, truy vấn DISTINCT từ bien_the_nuoc_hoa hoặc hardcode
// Ở đây hardcode các size phổ biến
$sizes = [
    ['dung_tich' => 30, 'label' => '30'],
    ['dung_tich' => 50, 'label' => '50'],
    ['dung_tich' => 100, 'label' => '100'],
    ['dung_tich' => 200, 'label' => '200']
]; // Có thể truy vấn DISTINCT nếu cần

// 3. XỬ LÝ KHI FORM ĐƯỢC SUBMIT (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Lấy dữ liệu chính
    $ten_nh = mysqli_real_escape_string($conn, $_POST['tennh']);
    $duong_dan = mysqli_real_escape_string($conn, $_POST['slug']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mota']);
    $mo_ta_ngan = mysqli_real_escape_string($conn, $_POST['short_desc']);
    $ma_dm = (int)$_POST['madm'];
    $ma_th = (int)$_POST['math'];
    
    // Lấy dữ liệu biến thể
    $variants = $_POST['variants'] ?? [];
    
    // 4. Validate Dữ liệu
    if (empty($ten_nh)) $errors[] = 'Vui lòng nhập Tên sản phẩm.';
    if (empty($duong_dan)) $errors[] = 'Vui lòng nhập Slug.';
    if (empty($mo_ta)) $errors[] = 'Vui lòng nhập Mô tả.';
    if ($ma_dm <= 0) $errors[] = 'Vui lòng chọn Danh mục.';
    if ($ma_th <= 0) $errors[] = 'Vui lòng chọn Thương hiệu.';
    
    $has_variant = false;
    foreach ($variants as $variant) {
        if (!empty($variant['gia']) && !empty($variant['soluong'])) {
            $has_variant = true;
            break;
        }
    }
    if (!$has_variant) {
        $errors[] = 'Vui lòng nhập ít nhất một biến thể (Giá và Số lượng).';
    }

    // 5. Xử lý Upload Ảnh
    $hinh_anh_path = '';
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
        $target_dir = "../public/img/"; 
        $ten_file_goc = basename($_FILES["hinhanh"]["name"]);
        $target_file = $target_dir . $ten_file_goc;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["hinhanh"]["tmp_name"]);
        if($check === false) {
            $errors[] = "File không phải là ảnh.";
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $errors[] = "Chỉ cho phép JPG, JPEG, PNG & GIF.";
        }

        if (file_exists($target_file)) {
            $errors[] = "File ảnh đã tồn tại.";
        }

        if ($_FILES["hinhanh"]["size"] > 5000000) { // 5MB
            $errors[] = "File ảnh quá lớn.";
        }

        if (empty($errors)) {
            if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file)) {
                $hinh_anh_path = "/public/img/" . $ten_file_goc;
            } else {
                $errors[] = "Lỗi khi tải ảnh lên.";
            }
        }
    } else {
        $errors[] = 'Vui lòng chọn ảnh sản phẩm.';
    }

    // 6. Nếu không lỗi, insert vào CSDL (sử dụng TRANSACTION)
    if (empty($errors)) {
        mysqli_begin_transaction($conn);
        try {
            // 6.1. Insert sản phẩm chính (nuoc_hoa)
            $sql_insert_product = "
                INSERT INTO nuoc_hoa 
                (ten_nuoc_hoa, duong_dan, mo_ta, mo_ta_ngan, ma_danh_muc, ma_thuong_hieu, hinh_anh) 
                VALUES 
                ('$ten_nh', '$duong_dan', '$mo_ta', '$mo_ta_ngan', $ma_dm, $ma_th, '$hinh_anh_path')
            ";
            mysqli_query($conn, $sql_insert_product);
            
            // 6.2. Lấy ID sản phẩm vừa insert (ma_nuoc_hoa)
            $ma_nh = mysqli_insert_id($conn);
            
            // 6.3. Insert các biến thể (bien_the_nuoc_hoa)
            foreach ($variants as $dung_tich => $variant) {
                $gia = (int)$variant['gia'];
                $soluong = (int)$variant['soluong'];
                if ($gia > 0 && $soluong >= 0) { // Chỉ insert nếu có dữ liệu
                    $sql_insert_variant = "
                        INSERT INTO bien_the_nuoc_hoa 
                        (ma_nuoc_hoa, dung_tich, gia_niem_yet, so_luong_ton) 
                        VALUES 
                        ($ma_nh, $dung_tich, $gia, $soluong)
                    ";
                    mysqli_query($conn, $sql_insert_variant);
                }
            }
            
            // 6.4. Commit nếu tất cả thành công
            mysqli_commit($conn);
            
            // 6.5. Chuyển hướng về trang sản phẩm
            header('Location: adminproducts.php?status=add_success');
            exit();
            
        } catch (Exception $e) {
            // 6.6. Rollback nếu lỗi
            mysqli_rollback($conn);
            $errors[] = 'Lỗi Transaction: ' . $e->getMessage();
        }
    }
    
} // Kết thúc xử lý POST

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Thêm Sản phẩm</title>
    <link rel="stylesheet" href="../public/css/style-admin.css" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body>
    <aside class="admin-sidebar" id="admin-sidebar">
        <!-- Giữ nguyên như cũ -->
    </aside>

    <div class="admin-main-content">
        <header class="admin-topbar">
            <!-- Giữ nguyên như cũ -->
        </header>

        <main class="admin-main-area">
            <div class="admin-container">
                <div class="mb-8">
                    <h1 class="page-title">Thêm Sản phẩm Mới</h1>
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
                
                    <form action="product_add.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="form-group-admin">
                            <label for="tennh" class="form-label-admin">Tên Sản phẩm</label>
                            <input type="text" id="tennh" name="tennh" class="form-input-admin" required>
                        </div>
                        
                        <div class="form-group-admin">
                            <label for="slug" class="form-label-admin">Slug (Đường dẫn)</label>
                            <input type="text" id="slug" name="slug" class="form-input-admin" 
                                   placeholder="vi-du: nuoc-hoa-chanel-no5" required>
                            <p class="form-hint-admin">Chỉ dùng chữ thường, số, và dấu gạch ngang (-).</p>
                        </div>
                        
                        <div class="form-group-admin">
                            <label for="mota" class="form-label-admin">Mô tả chi tiết</label>
                            <textarea id="mota" name="mota" class="form-textarea-admin" rows="6" required></textarea>
                        </div>
                        
                        <div class="form-group-admin">
                            <label for="short_desc" class="form-label-admin">Mô tả ngắn</label>
                            <textarea id="short_desc" name="short_desc" class="form-textarea-admin" rows="3"></textarea>
                        </div>
                        
                        <div style="display: flex; gap: 20px;">
                            <div class="form-group-admin" style="flex: 1;">
                                <label for="madm" class="form-label-admin">Danh mục</label>
                                <select id="madm" name="madm" class="form-input-admin" required>
                                    <option value="">Chọn danh mục</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['ma_danh_muc']; ?>">
                                            <?php echo htmlspecialchars($category['ten_danh_muc']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group-admin" style="flex: 1;">
                                <label for="math" class="form-label-admin">Thương hiệu</label>
                                <select id="math" name="math" class="form-input-admin" required>
                                    <option value="">Chọn thương hiệu</option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?php echo $brand['ma_thuong_hieu']; ?>">
                                            <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group-admin">
                            <label for="hinhanh" class="form-label-admin">Hình ảnh sản phẩm</label>
                            <input type="file" id="hinhanh" name="hinhanh" class="form-input-admin" accept="image/*" required>
                            <p class="form-hint-admin">Chọn ảnh JPG/PNG, kích thước dưới 5MB.</p>
                        </div>
                        
                        <hr style="border-color: var(--color-border); margin: 20px 0;">
                        
                        <div class="form-group-admin">
                            <label class="form-label-admin">Biến thể (Dung tích & Giá)</label>
                            <p class="form-hint-admin">Bỏ trống các dung tích không áp dụng. Phải nhập ít nhất 1 loại.</p>

                            <?php 
                            // Dùng vòng lặp để tạo các ô input cho từng dung tích
                            foreach ($sizes as $size): 
                                $dung_tich = $size['dung_tich'];
                            ?>
                                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                                    <label class="form-label-admin" style="width: 80px; margin: 0;">
                                        <?php echo $size['label']; ?>ml:
                                    </label>
                                    <div style="flex: 1;">
                                        <label class="form-label-admin" style="font-size: 12px; margin-bottom: 2px;">Giá (VND)</label>
                                        <input type="number" name="variants[<?php echo $dung_tich; ?>][gia]" class="form-input-admin" placeholder="Giá (ví dụ: 1500000)">
                                    </div>
                                    <div style="flex: 1;">
                                        <label class="form-label-admin" style="font-size: 12px; margin-bottom: 2px;">Số lượng tồn</label>
                                        <input type="number" name="variants[<?php echo $dung_tich; ?>][soluong]" class="form-input-admin" placeholder="Tồn kho (ví dụ: 10)">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="form-actions-admin" style="margin-top: 20px;">
                                <button type="submit" class="btn-primary-admin">
                                    <span class="material-symbols-outlined">add_circle</span>
                                    Thêm Sản phẩm
                                </button>
                                <a href="adminproducts.php" class="btn-secondary-admin" style="text-decoration: none;">
                                    <span class="material-symbols-outlined">cancel</span>
                                    Hủy
                                </a>
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