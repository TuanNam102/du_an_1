<?php
include 'check_admin.php'; 
include '../config/db_connect.php';

$ten_dm = '';
$duong_dan = '';
$ma_dm = 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_dm = (int)$_POST['madm'];
    $ten_dm = mysqli_real_escape_string($conn, $_POST['tendm']);
    $duong_dan = mysqli_real_escape_string($conn, $_POST['slug']);

    if (empty($ten_dm)) $errors[] = 'Vui lòng nhập Tên Danh mục.';
    if (empty($duong_dan)) $errors[] = 'Vui lòng nhập Slug.';

    $sql_check_slug = "SELECT ma_danh_muc FROM danh_muc WHERE duong_dan = '$duong_dan' AND ma_danh_muc != $ma_dm";
    $result_check = mysqli_query($conn, $sql_check_slug);
    if (mysqli_num_rows($result_check) > 0) $errors[] = 'Slug đã tồn tại.';

    if (empty($errors)) {
        $sql_update = "UPDATE danh_muc SET ten_danh_muc = '$ten_dm', duong_dan = '$duong_dan' WHERE ma_danh_muc = $ma_dm";
        if (mysqli_query($conn, $sql_update)) {
            header('Location: admincategories.php?status=edit_success');
            exit();
        } else {
            $errors[] = 'Lỗi CSDL: ' . mysqli_error($conn);
        }
    }
} elseif (isset($_GET['id'])) {
    $ma_dm = (int)$_GET['id'];
    $sql_get = "SELECT ten_danh_muc, duong_dan FROM danh_muc WHERE ma_danh_muc = $ma_dm";
    $result_get = mysqli_query($conn, $sql_get);
    if (mysqli_num_rows($result_get) == 1) {
        $category = mysqli_fetch_assoc($result_get);
        $ten_dm = $category['ten_danh_muc'];
        $duong_dan = $category['duong_dan'];
    } else {
        header('Location: admincategories.php');
        exit();
    }
} else {
    header('Location: admincategories.php');
    exit();
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>SAPHIRA - Sửa Danh mục</title>
    <link rel="stylesheet" href="../public/css/style-admin.css" />
</head>
<body>
    <!-- Giữ nguyên HTML form, chỉ thay name slug -> duong_dan nếu cần (ở đây giữ slug) -->
</body>
</html>