<?php
session_start();
include 'config/db_connect.php';
if (!isset($_SESSION['user_id'])) { header('Location: main.php?r=login'); exit(); }
if (!isset($_GET['id']) || !isset($_GET['change'])) { header('Location: main.php?r=cart'); exit(); }
$item_id = (int)$_GET['id'];
$change = (int)$_GET['change'];
$user_id = (int)$_SESSION['user_id'];
$stmt_cart = mysqli_prepare($conn, "SELECT ma_gio_hang FROM gio_hang WHERE ma_nguoi_dung = ?");
mysqli_stmt_bind_param($stmt_cart, "i", $user_id);
mysqli_stmt_execute($stmt_cart);
$res_cart = mysqli_stmt_get_result($stmt_cart);
if (!$res_cart || mysqli_num_rows($res_cart) === 0) { header('Location: main.php?r=cart'); exit(); }
$cart_row = mysqli_fetch_assoc($res_cart);
$cart_id = (int)$cart_row['ma_gio_hang'];
$stmt_check = mysqli_prepare($conn, "SELECT so_luong FROM mat_hang_gio_hang WHERE ma_mat_hang = ? AND ma_gio_hang = ?");
mysqli_stmt_bind_param($stmt_check, "ii", $item_id, $cart_id);
mysqli_stmt_execute($stmt_check);
$res_check = mysqli_stmt_get_result($stmt_check);
if ($res_check && mysqli_num_rows($res_check) > 0) {
    $row = mysqli_fetch_assoc($res_check);
    $current_qty = (int)$row['so_luong'];
    $new_qty = $current_qty + $change;
    if ($new_qty > 0) {
        $stmt_update = mysqli_prepare($conn, "UPDATE mat_hang_gio_hang SET so_luong = ? WHERE ma_mat_hang = ? AND ma_gio_hang = ?");
        mysqli_stmt_bind_param($stmt_update, "iii", $new_qty, $item_id, $cart_id);
        mysqli_stmt_execute($stmt_update);
    } else {
        $stmt_delete = mysqli_prepare($conn, "DELETE FROM mat_hang_gio_hang WHERE ma_mat_hang = ? AND ma_gio_hang = ?");
        mysqli_stmt_bind_param($stmt_delete, "ii", $item_id, $cart_id);
        mysqli_stmt_execute($stmt_delete);
    }
}
mysqli_close($conn);
header('Location: main.php?r=cart');
exit();
?>
