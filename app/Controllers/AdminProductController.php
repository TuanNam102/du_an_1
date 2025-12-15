<?php
class AdminProductController {
    public function index(): void {
        require __DIR__ . '/../../admin/check_admin.php';
        require __DIR__ . '/../../config/db_connect.php';

        $products = [];
        $sql_products = "
            SELECT 
                n.ma_nuoc_hoa AS MaNH,
                n.ten_nuoc_hoa AS TenNH,
                dm.ten_danh_muc AS TenDM,
                MIN(b.gia_niem_yet) AS GiaThap,
                SUM(b.so_luong_ton) AS TongTonKho,
                MIN(h.duong_dan_hinh) AS HinhAnh
            FROM nuoc_hoa n
            LEFT JOIN danh_muc dm ON n.ma_danh_muc = dm.ma_danh_muc
            LEFT JOIN bien_the_nuoc_hoa b ON n.ma_nuoc_hoa = b.ma_nuoc_hoa
            LEFT JOIN hinh_anh_nuoc_hoa h ON n.ma_nuoc_hoa = h.ma_nuoc_hoa AND (h.loai_hinh_anh = 'anh_lon' OR h.loai_hinh_anh IS NULL)
            GROUP BY n.ma_nuoc_hoa, n.ten_nuoc_hoa, dm.ten_danh_muc
            ORDER BY n.ma_nuoc_hoa ASC;
        ";

        $result_products = mysqli_query($conn, $sql_products);
        if ($result_products) { $products = mysqli_fetch_all($result_products, MYSQLI_ASSOC); }
        foreach ($products as &$p) {
            $p['HinhAnh'] = isset($p['HinhAnh']) && $p['HinhAnh'] ? ('public' . $p['HinhAnh']) : 'public/img/placeholder.jpg';
            $p['GiaThap'] = (int)($p['GiaThap'] ?? 0);
            $p['TongTonKho'] = (int)($p['TongTonKho'] ?? 0);
        }

        mysqli_close($conn);

        require __DIR__ . '/../../admin/adminproducts.php';
    }
}
