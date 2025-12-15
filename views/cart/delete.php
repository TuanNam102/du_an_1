<?php
session_start();
include 'config/db_connect.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) { header('Location: main.php?r=cart'); exit(); }
$item_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user_id'];
$stmt_cart = mysqli_prepare($conn, "SELECT ma_gio_hang FROM gio_hang WHERE ma_nguoi_dung = ?");
mysqli_stmt_bind_param($stmt_cart, "i", $user_id);
mysqli_stmt_execute($stmt_cart);
$res_cart = mysqli_stmt_get_result($stmt_cart);
if ($res_cart && mysqli_num_rows($res_cart) > 0) {
    $cart = mysqli_fetch_assoc($res_cart);
    $cart_id = (int)$cart['ma_gio_hang'];
    $stmt_del = mysqli_prepare($conn, "DELETE FROM mat_hang_gio_hang WHERE ma_mat_hang = ? AND ma_gio_hang = ?");
    mysqli_stmt_bind_param($stmt_del, "ii", $item_id, $cart_id);
    mysqli_stmt_execute($stmt_del);
}
mysqli_close($conn);
header('Location: main.php?r=cart');
exit();
?>
