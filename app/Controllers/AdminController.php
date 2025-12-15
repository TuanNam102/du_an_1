<?php
class AdminController {
    public function dashboard(): void {
        require __DIR__ . '/../../admin/check_admin.php';
        require __DIR__ . '/../../config/db_connect.php';

        $total_orders = 0;
        $total_revenue = 0;
        $total_customers = 0;
        $total_products = 0;
        $recent_orders = [];

        $res = mysqli_query($conn, "SELECT COUNT(ma_don_hang) as total FROM don_hang");
        if ($res) { $row = mysqli_fetch_assoc($res); $total_orders = (int)($row['total'] ?? 0); }

        $res = mysqli_query($conn, "SELECT SUM(tong_tien) as total FROM don_hang");
        if ($res) { $row = mysqli_fetch_assoc($res); $total_revenue = (int)($row['total'] ?? 0); }

        $res = mysqli_query($conn, "SELECT COUNT(ma_nguoi_dung) as total FROM nguoi_dung WHERE vai_tro = 'khach_hang'");
        if ($res) { $row = mysqli_fetch_assoc($res); $total_customers = (int)($row['total'] ?? 0); }

        $res = mysqli_query($conn, "SELECT COUNT(ma_nuoc_hoa) as total FROM nuoc_hoa");
        if ($res) { $row = mysqli_fetch_assoc($res); $total_products = (int)($row['total'] ?? 0); }

        $sql_recent = "
            SELECT 
                dh.ma_don_hang AS DonHangID,
                nd.ho_ten AS HoTen,
                dh.thoi_gian_dat AS NgayTT,
                dh.tong_tien AS TongDonHang,
                CASE 
                    WHEN dh.trang_thai_don IN ('da_thanh_toan','hoan_thanh','giao_hang_thanh_cong') THEN 1 
                    ELSE 0 
                END AS DaThanhToan
            FROM don_hang dh
            JOIN nguoi_dung nd ON dh.ma_nguoi_dung = nd.ma_nguoi_dung
            ORDER BY dh.thoi_gian_dat DESC
            LIMIT 5
        ";
        $result_recent = mysqli_query($conn, $sql_recent);
        if ($result_recent) { $recent_orders = mysqli_fetch_all($result_recent, MYSQLI_ASSOC); }

        mysqli_close($conn);

        require __DIR__ . '/../../admin/admin.php';
    }
}
