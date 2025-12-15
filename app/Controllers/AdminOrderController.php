<?php
class AdminOrderController {
    public function index(): void {
        require __DIR__ . '/../../admin/check_admin.php';
        require __DIR__ . '/../../config/db_connect.php';

        $orders = [];
        $sql_orders = "
            SELECT 
                dh.ma_don_hang AS DonHangID, 
                nd.ho_ten AS HoTen, 
                dh.thoi_gian_dat AS NgayTT, 
                dh.tong_tien AS TongDonHang, 
                CASE 
                    WHEN dh.trang_thai_don IN ('da_thanh_toan','hoan_thanh','giao_hang_thanh_cong') THEN 1 
                    ELSE 0 
                END AS DaThanhToan
            FROM 
                don_hang dh
            JOIN 
                nguoi_dung nd ON dh.ma_nguoi_dung = nd.ma_nguoi_dung
            ORDER BY dh.thoi_gian_dat DESC;
        ";

        $result_orders = mysqli_query($conn, $sql_orders);
        if ($result_orders) { $orders = mysqli_fetch_all($result_orders, MYSQLI_ASSOC); }

        mysqli_close($conn);

        require __DIR__ . '/../../admin/admindonhang.php';
    }
}
