<?php
// 1. GỌI FILE BẢO VỆ
include 'check_admin.php'; 

$ten_dm = '';
$slug = '';
$errors = [];

// 2. XỬ LÝ POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config/db_connect.php';
    $ten_dm = mysqli_real_escape_string($conn, $_POST['tendm']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);

    if (empty($ten_dm)) $errors[] = 'Vui lòng nhập Tên Danh mục.';
    if (empty($slug)) $errors[] = 'Vui lòng nhập Slug.';

    $sql_check_slug = "SELECT ma_danh_muc FROM danh_muc WHERE duong_dan = '$slug'";
    $result_check = mysqli_query($conn, $sql_check_slug);
    if (mysqli_num_rows($result_check) > 0) $errors[] = 'Slug đã tồn tại.';

    if (empty($errors)) {
        $sql_insert = "INSERT INTO danh_muc (ten_danh_muc, duong_dan) VALUES ('$ten_dm', '$slug')";
        if (mysqli_query($conn, $sql_insert)) {
            header('Location: admincategories.php?status=add_success');
            exit();
        } else {
            $errors[] = 'Lỗi CSDL: ' . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>SAPHIRA - Thêm Danh mục</title>
    <link rel="stylesheet" href="../public/css/style-admin.css" />
</head>
<body>
    <!-- Giữ nguyên HTML form như cũ, chỉ thay action và name nếu cần (ở đây giữ nguyên) -->
</body>
</html>