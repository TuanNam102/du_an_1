<?php
include 'check_admin.php'; 
if (!isset($_GET['id'])) {
    header('Location: admincategories.php');
    exit();
}
include '../config/db_connect.php';
$ma_dm = (int)$_GET['id'];
$sql_delete = "DELETE FROM danh_muc WHERE ma_danh_muc = $ma_dm";
if (mysqli_query($conn, $sql_delete)) {
    header('Location: admincategories.php?status=delete_success');
} else {
    header('Location: admincategories.php?status=delete_error&msg=' . urlencode('Không thể xóa vì còn sản phẩm.'));
}
mysqli_close($conn);
exit();
?>