<?php
include 'check_admin.php'; 
if (!isset($_GET['id'])) {
    header('Location: admincustomers.php?status=delete_error&msg=no_id');
    exit();
}
include '../config/db_connect.php';
$ma_nd_xoa = (int)$_GET['id'];

// Không tự xóa mình
if ($ma_nd_xoa == $_SESSION['ma_nguoi_dung']) { // Giả sử session có ma_nguoi_dung
    header('Location: admincustomers.php?status=delete_error&msg=' . urlencode('Không thể tự xóa.'));
    exit();
}

// Kiểm tra admin cuối cùng
$sql_check_admin = "SELECT COUNT(*) as total FROM nguoi_dung WHERE vai_tro = 'quan_tri'";
$result_check = mysqli_query($conn, $sql_check_admin);
$row = mysqli_fetch_assoc($result_check);
if ($row['total'] <= 1) {
    $sql_is_admin = "SELECT vai_tro FROM nguoi_dung WHERE ma_nguoi_dung = $ma_nd_xoa";
    $result_is_admin = mysqli_query($conn, $sql_is_admin);
    $user = mysqli_fetch_assoc($result_is_admin);
    if ($user['vai_tro'] == 'quan_tri') {
        header('Location: admincustomers.php?status=delete_error&msg=' . urlencode('Không thể xóa admin cuối cùng.'));
        exit();
    }
}

mysqli_begin_transaction($conn);
try {
    // Xóa giỏ hàng, đơn hàng, chi tiết đơn hàng, người dùng (tương tự cũ nhưng thay tên bảng/cột)
    $sql_del_cart = "DELETE FROM gio_hang WHERE ma_nguoi_dung = $ma_nd_xoa";
    mysqli_query($conn, $sql_del_cart);

    $sql_get_orders = "SELECT ma_don_hang FROM don_hang WHERE ma_nguoi_dung = $ma_nd_xoa";
    $result_orders = mysqli_query($conn, $sql_get_orders);
    $order_ids = [];
    while($row = mysqli_fetch_assoc($result_orders)) {
        $order_ids[] = $row['ma_don_hang'];
    }
    if (!empty($order_ids)) {
        $ids_string = implode(',', $order_ids);
        $sql_del_items = "DELETE FROM chi_tiet_don_hang WHERE ma_don_hang IN ($ids_string)";
        mysqli_query($conn, $sql_del_items);
    }

    $sql_del_orders = "DELETE FROM don_hang WHERE ma_nguoi_dung = $ma_nd_xoa";
    mysqli_query($conn, $sql_del_orders);

    $sql_del_user = "DELETE FROM nguoi_dung WHERE ma_nguoi_dung = $ma_nd_xoa";
    mysqli_query($conn, $sql_del_user);

    mysqli_commit($conn);
    header('Location: admincustomers.php?status=delete_success');
} catch (Exception $e) {
    mysqli_rollback($conn);
    header('Location: admincustomers.php?status=delete_error&msg=' . urlencode($e->getMessage()));
}
mysqli_close($conn);
exit();
?>