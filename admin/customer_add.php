<?php
include 'check_admin.php'; 

$ho_ten = ''; $email = ''; $sdt = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config/db_connect.php';
    $ho_ten = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sdt = mysqli_real_escape_string($conn, $_POST['phone']);
    $mat_khau = $_POST['password'];
    $xac_nhan_mat_khau = $_POST['password_confirm'];
    $vai_tro = ($_POST['quyentc'] == 1) ? 'quan_tri' : 'khach_hang';
    $trang_thai = (int)$_POST['trangthai'];

    if (empty($ho_ten)) $errors[] = 'Vui lòng nhập họ và tên.';
    if (empty($email)) $errors[] = 'Vui lòng nhập email.';
    if (empty($mat_khau)) $errors[] = 'Vui lòng nhập mật khẩu.';
    if ($mat_khau != $xac_nhan_mat_khau) $errors[] = 'Mật khẩu không khớp.';

    $sql_check = "SELECT email FROM nguoi_dung WHERE email = '$email'";
    $result_check = mysqli_query($conn, $sql_check);
    if (mysqli_num_rows($result_check) > 0) $errors[] = 'Email đã tồn tại.';

    if (empty($errors)) {
        $mat_khau_hashed = md5($mat_khau); // Giữ md5 nếu cần khớp cũ
        $sql_insert = "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, vai_tro, trang_thai) VALUES ('$ho_ten', '$email', '$mat_khau_hashed', '$sdt', '$vai_tro', $trang_thai)";
        if (mysqli_query($conn, $sql_insert)) {
            header('Location: admincustomers.php?status=add_success');
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
    <title>SAPHIRA - Thêm Người dùng</title>
    <link rel="stylesheet" href="../public/css/style-admin.css" />
</head>
<body>
    <!-- Giữ nguyên HTML form, chỉ thay username -> email nếu cần (ở đây thay name="username" thành name="email") -->
</body>
</html>