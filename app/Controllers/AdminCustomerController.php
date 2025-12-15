<?php
class AdminCustomerController {
    public function index(): void {
        require __DIR__ . '/../../admin/check_admin.php';
        require __DIR__ . '/../../config/db_connect.php';

        $customers = [];
        $sql_customers = "
            SELECT 
                nd.ma_nguoi_dung AS TenDN,
                nd.ho_ten AS HoTen,
                nd.email AS Email,
                nd.so_dien_thoai AS SoDienThoai,
                CASE WHEN nd.vai_tro = 'quan_tri' THEN 1 ELSE 2 END AS QuyenTC,
                CASE WHEN nd.trang_thai = 1 THEN 1 ELSE 0 END AS TrangThai
            FROM 
                nguoi_dung nd
            ORDER BY 
                QuyenTC ASC, HoTen ASC;
        ";

        $result_customers = mysqli_query($conn, $sql_customers);
        if ($result_customers) { $customers = mysqli_fetch_all($result_customers, MYSQLI_ASSOC); }

        mysqli_close($conn);

        require __DIR__ . '/../../admin/admincustomers.php';
    }
}

