<?php
class AdminCategoryController {
    public function index(): void {
        require __DIR__ . '/../../admin/check_admin.php';
        require __DIR__ . '/../../config/db_connect.php';

        $categories = [];
        $sql_categories = "
            SELECT 
                dm.ma_danh_muc AS MaDM,
                dm.ten_danh_muc AS TenDM,
                COUNT(nh.ma_nuoc_hoa) AS SoLuongSanPham
            FROM 
                danh_muc dm
            LEFT JOIN 
                nuoc_hoa nh ON dm.ma_danh_muc = nh.ma_danh_muc
            GROUP BY 
                dm.ma_danh_muc, dm.ten_danh_muc
            ORDER BY 
                dm.ma_danh_muc ASC;
        ";

        $result_categories = mysqli_query($conn, $sql_categories);
        if ($result_categories) { $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC); }

        mysqli_close($conn);

        require __DIR__ . '/../../admin/admincategories.php';
    }
}
