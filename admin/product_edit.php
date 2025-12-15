<?php
include 'check_admin.php'; 
include '../config/db_connect.php';

$errors = [];
$ma_nh = 0;
$product_variants = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_nh = (int)$_POST['manh'];
    $ten_nh = mysqli_real_escape_string($conn, $_POST['tennh']);
    $duong_dan = mysqli_real_escape_string($conn, $_POST['slug']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mota']);
    $short_desc = mysqli_real_escape_string($conn, $_POST['short_desc']);
    $ma_dm = (int)$_POST['madm'];
    $ma_th = (int)$_POST['math'];
    $hinh_anh_cu = mysqli_real_escape_string($conn, $_POST['hinh_anh_cu']);

    $variants = $_POST['variants'] ?? [];
    $has_variant = false;
    $gia_thap = PHP_INT_MAX;
    $gia_cao = 0;
    foreach ($variants as $variant) {
        if (!empty($variant['gia']) && !empty($variant['soluong'])) {
            $has_variant = true;
            $gia = (int)$variant['gia'];
            if ($gia < $gia_thap) $gia_thap = $gia;
            if ($gia > $gia_cao) $gia_cao = $gia;
        }
    }
    if (!$has_variant) $errors[] = 'Cần ít nhất một biến thể.';

    $hinh_anh_path = $hinh_anh_cu;
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
        $target_dir = "../public/img/";
        $ten_file = basename($_FILES["hinhanh"]["name"]);
        $target_file = $target_dir . $ten_file;
        if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file)) {
            $hinh_anh_path = "/public/img/" . $ten_file;
        } else {
            $errors[] = "Lỗi tải ảnh.";
        }
    }

    if (empty($errors)) {
        mysqli_begin_transaction($conn);
        try {
            $sql_update = "UPDATE nuoc_hoa SET ten_nuoc_hoa = '$ten_nh', duong_dan = '$duong_dan', mo_ta = '$mo_ta', mo_ta_ngan = '$short_desc', ma_danh_muc = $ma_dm, ma_thuong_hieu = $ma_th, hinh_anh = '$hinh_anh_path' WHERE ma_nuoc_hoa = $ma_nh";
            mysqli_query($conn, $sql_update);

            foreach ($variants as $ma_dt => $variant) {
                $gia = (int)$variant['gia'];
                $soluong = (int)$variant['soluong'];
                if ($gia > 0 && $soluong >= 0) {
                    $sql_variant = "REPLACE INTO bien_the_nuoc_hoa (ma_nuoc_hoa, dung_tich, gia_niem_yet, so_luong_ton) VALUES ($ma_nh, $ma_dt, $gia, $soluong)";
                    mysqli_query($conn, $sql_variant);
                }
            }

            mysqli_commit($conn);
            header('Location: adminproducts.php?status=edit_success');
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $errors[] = 'Lỗi: ' . $e->getMessage();
        }
    }
} elseif (isset($_GET['id'])) {
    $ma_nh = (int)$_GET['id'];
    $sql_product = "SELECT * FROM nuoc_hoa WHERE ma_nuoc_hoa = $ma_nh";
    $result_product = mysqli_query($conn, $sql_product);
    $product = mysqli_fetch_assoc($result_product);

    $sql_variants = "SELECT * FROM bien_the_nuoc_hoa WHERE ma_nuoc_hoa = $ma_nh";
    $result_variants = mysqli_query($conn, $sql_variants);
    while ($variant = mysqli_fetch_assoc($result_variants)) {
        $product_variants[$variant['dung_tich_ml']] = $variant;
    }

    $sql_categories = "SELECT ma_danh_muc, ten_danh_muc FROM danh_muc";
    $result_categories = mysqli_query($conn, $sql_categories);
    $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

    $sql_brands = "SELECT ma_thuong_hieu, ten_thuong_hieu FROM thuong_hieu";
    $result_brands = mysqli_query($conn, $sql_brands);
    $brands = mysqli_fetch_all($result_brands, MYSQLI_ASSOC);

    $sql_sizes = "SELECT DISTINCT dung_tich_ml FROM bien_the_nuoc_hoa ORDER BY dung_tich_ml";
    $result_sizes = mysqli_query($conn, $sql_sizes);
    $sizes = mysqli_fetch_all($result_sizes, MYSQLI_ASSOC);
} else {
    header('Location: adminproducts.php');
    exit();
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>SAPHIRA - Sửa Sản phẩm</title>
    <link rel="stylesheet" href="../public/css/style-admin.css" />
</head>
<body>
    <!-- Giữ nguyên HTML form, điều chỉnh name slug -> duong_dan, GiaThap -> gia_niem_yet min, v.v. nhưng giữ giao diện -->
</body>
</html>