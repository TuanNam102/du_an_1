<?php
session_start();
include 'config/db_connect.php';
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['HTTP_REFERER'];
    header('Location: main.php?r=login');
    exit();
}
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
    $cart_id = 0;
    $stmt_check_cart = mysqli_prepare($conn, "SELECT ma_gio_hang FROM gio_hang WHERE ma_nguoi_dung = ?");
    mysqli_stmt_bind_param($stmt_check_cart, "i", $user_id);
    mysqli_stmt_execute($stmt_check_cart);
    $res_check_cart = mysqli_stmt_get_result($stmt_check_cart);
    if ($res_check_cart && mysqli_num_rows($res_check_cart) > 0) {
        $cart = mysqli_fetch_assoc($res_check_cart);
        $cart_id = (int) $cart['ma_gio_hang'];
    } else {
        $stmt_create_cart = mysqli_prepare($conn, "INSERT INTO gio_hang (ma_nguoi_dung) VALUES (?)");
        mysqli_stmt_bind_param($stmt_create_cart, "i", $user_id);
        if (mysqli_stmt_execute($stmt_create_cart)) {
            $cart_id = mysqli_insert_id($conn);
        } else {
            die("Lỗi hệ thống: Không thể tạo giỏ hàng.");
        }
    }
    $variant_id = null;
    $price = null;
    if (isset($_POST['variant_id'])) {
        $variant_try = (int) $_POST['variant_id'];
        $stmt_variant = mysqli_prepare($conn, "SELECT ma_bien_the, gia_niem_yet FROM bien_the_nuoc_hoa WHERE ma_bien_the = ? AND ma_nuoc_hoa = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt_variant, "ii", $variant_try, $product_id);
        mysqli_stmt_execute($stmt_variant);
        $res_variant = mysqli_stmt_get_result($stmt_variant);
    } else {
        $stmt_get_variant = mysqli_prepare($conn, "SELECT ma_bien_the, gia_niem_yet FROM bien_the_nuoc_hoa WHERE ma_nuoc_hoa = ? ORDER BY gia_niem_yet ASC LIMIT 1");
        mysqli_stmt_bind_param($stmt_get_variant, "i", $product_id);
        mysqli_stmt_execute($stmt_get_variant);
        $res_variant = mysqli_stmt_get_result($stmt_get_variant);
    }
    if (mysqli_num_rows($res_variant) > 0) {
        $variant = mysqli_fetch_assoc($res_variant);
        $variant_id = (int) $variant['ma_bien_the'];
        $price = (int) $variant['gia_niem_yet'];
        $stmt_check_item = mysqli_prepare($conn, "SELECT ma_mat_hang, so_luong FROM mat_hang_gio_hang WHERE ma_gio_hang = ? AND ma_bien_the = ?");
        mysqli_stmt_bind_param($stmt_check_item, "ii", $cart_id, $variant_id);
        mysqli_stmt_execute($stmt_check_item);
        $res_item = mysqli_stmt_get_result($stmt_check_item);
        if (mysqli_num_rows($res_item) > 0) {
            $item = mysqli_fetch_assoc($res_item);
            $new_qty = ((int) $item['so_luong']) + $quantity;
            $item_id = (int) $item['ma_mat_hang'];
            $stmt_update = mysqli_prepare($conn, "UPDATE mat_hang_gio_hang SET so_luong = ? WHERE ma_mat_hang = ? AND ma_gio_hang = ?");
            mysqli_stmt_bind_param($stmt_update, "iii", $new_qty, $item_id, $cart_id);
            mysqli_stmt_execute($stmt_update);
        } else {
            $stmt_insert_item = mysqli_prepare($conn, "INSERT INTO mat_hang_gio_hang (ma_gio_hang, ma_bien_the, so_luong, gia) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt_insert_item, "iiii", $cart_id, $variant_id, $quantity, $price);
            mysqli_stmt_execute($stmt_insert_item);
        }
        header('Location: main.php?r=cart');
        exit();
    } else {
        echo "<script>alert('Sản phẩm này tạm thời hết hàng!'); window.history.back();</script>";
    }
} else {
    header('Location: main.php?r=home');
    exit();
}
mysqli_close($conn);
?>