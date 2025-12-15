<?php
// 1. GỌI FILE BẢO VỆ
include 'check_admin.php'; 

// 2. KIỂM TRA XEM CÓ ID ĐƯỢC GỬI ĐẾN KHÔNG
if ( !isset($_GET['id']) ) {
    header('Location: adminproducts.php?status=delete_error&msg=no_id');
    exit();
}

// 3. KẾT NỐI CSDL
include '../config/db_connect.php';

// 4. LẤY VÀ LÀM SẠCH ID SẢN PHẨM (ma_nuoc_hoa)
$ma_nh = (int)$_GET['id'];

if ($ma_nh <= 0) {
    header('Location: adminproducts.php?status=delete_error&msg=invalid_id');
    exit();
}

// 5. BẮT ĐẦU TRANSACTION
mysqli_begin_transaction($conn);

try {
    // 5.1. Lấy tất cả các ma_bien_the của sản phẩm này
    $sql_get_variants = "SELECT ma_bien_the FROM bien_the_nuoc_hoa WHERE ma_nuoc_hoa = $ma_nh";
    $result_variants = mysqli_query($conn, $sql_get_variants);
    $variants_ids = [];
    while($row = mysqli_fetch_assoc($result_variants)) {
        $variants_ids[] = $row['ma_bien_the'];
    }
    mysqli_free_result($result_variants);

    if ( !empty($variants_ids) ) {
        // Chuyển mảng [1, 2, 3] thành chuỗi "1,2,3"
        $ids_string = implode(',', $variants_ids);

        // 5.2. Xóa khỏi mat_hang_gio_hang
        $sql_delete_cart = "DELETE FROM mat_hang_gio_hang WHERE ma_bien_the IN ($ids_string)";
        mysqli_query($conn, $sql_delete_cart);

        // 5.3. Xóa khỏi chi_tiet_don_hang
        $sql_delete_order_items = "DELETE FROM chi_tiet_don_hang WHERE ma_bien_the IN ($ids_string)";
        mysqli_query($conn, $sql_delete_order_items);
        
        // 5.4. Xóa khỏi bien_the_nuoc_hoa
        $sql_delete_variants = "DELETE FROM bien_the_nuoc_hoa WHERE ma_nuoc_hoa = $ma_nh";
        mysqli_query($conn, $sql_delete_variants);
    }

    // 5.5. Xóa sản phẩm chính (nuoc_hoa)
    $sql_delete_product = "DELETE FROM nuoc_hoa WHERE ma_nuoc_hoa = $ma_nh";
    mysqli_query($conn, $sql_delete_product);

    // 5.6. Nếu tất cả đều thành công, commit transaction
    mysqli_commit($conn);
    
    // (Xóa file ảnh nếu cần: unlink($path); nhưng cần lấy hinh_anh trước)
    
    // 5.7. Chuyển hướng
    header('Location: adminproducts.php?status=delete_success');

} catch (Exception $e) {
    // 5.8. Nếu có bất kỳ lỗi nào, hủy bỏ (rollback) tất cả
    mysqli_rollback($conn);
    
    // 5.9. Chuyển hướng với thông báo lỗi
    $error_msg = urlencode('Lỗi khi xóa sản phẩm: ' . $e->getMessage());
    header('Location: adminproducts.php?status=delete_error&msg=' . $error_msg);
}

// 6. ĐÓNG KẾT NỐI
mysqli_close($conn);
exit();
?>