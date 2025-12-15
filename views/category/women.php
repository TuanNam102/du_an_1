<?php
session_start();
$active_page = 'women';
$page_title = 'Nước Hoa Nữ';
include __DIR__ . '/../../config/db_connect.php';
include __DIR__ . '/../../config/helpers.php';

$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

$where_clause = "WHERE n.ma_gioi_tinh = 2 AND (h.loai_hinh_anh = 'anh_lon' OR h.loai_hinh_anh IS NULL)";
if ($filter == 'hoa-co') {
    $where_clause .= " AND (n.mo_ta_chi_tiet LIKE '%hoa%' OR n.mo_ta_chi_tiet LIKE '%cỏ%')";
} elseif ($filter == 'ngot-ngao') {
    $where_clause .= " AND (n.mo_ta_chi_tiet LIKE '%ngọt%' OR n.mo_ta_chi_tiet LIKE '%vanilla%')";
} elseif ($filter == 'phun') {
    $where_clause .= " AND (n.mo_ta_chi_tiet LIKE '%phấn%' OR n.mo_ta_chi_tiet LIKE '%ấm%')";
} elseif ($filter == 'trai-cay') {
    $where_clause .= " AND (n.mo_ta_chi_tiet LIKE '%trái cây%' OR n.mo_ta_chi_tiet LIKE '%tropical%')";
} elseif ($filter == 'goi-cam') {
    $where_clause .= " AND (n.mo_ta_chi_tiet LIKE '%gợi cảm%' OR n.mo_ta_chi_tiet LIKE '%sexy%')";
}

$order_by = "ORDER BY n.thoi_gian_tao DESC";
if ($sort == 'best-seller') {
    $order_by = "ORDER BY n.ma_nuoc_hoa ASC";
} elseif ($sort == 'price-asc') {
    $order_by = "ORDER BY gia_thap_nhat ASC";
} elseif ($sort == 'price-desc') {
    $order_by = "ORDER BY gia_thap_nhat DESC";
}

$sql_count = "SELECT COUNT(DISTINCT n.ma_nuoc_hoa) as total FROM nuoc_hoa n JOIN bien_the_nuoc_hoa b ON n.ma_nuoc_hoa = b.ma_nuoc_hoa LEFT JOIN hinh_anh_nuoc_hoa h ON n.ma_nuoc_hoa = h.ma_nuoc_hoa $where_clause";
$result_count = mysqli_query($conn, $sql_count);
$row_count = $result_count ? mysqli_fetch_assoc($result_count) : ['total' => 0];
$total_products = (int)$row_count['total'];
$total_pages = $total_products > 0 ? ceil($total_products / $limit) : 1;

$sql_products = "SELECT n.ma_nuoc_hoa, n.ten_nuoc_hoa, n.duong_dan, MIN(b.gia_niem_yet) as gia_thap_nhat, MIN(h.duong_dan_hinh) as hinh_anh FROM nuoc_hoa n JOIN bien_the_nuoc_hoa b ON n.ma_nuoc_hoa = b.ma_nuoc_hoa LEFT JOIN hinh_anh_nuoc_hoa h ON n.ma_nuoc_hoa = h.ma_nuoc_hoa $where_clause GROUP BY n.ma_nuoc_hoa, n.ten_nuoc_hoa, n.duong_dan $order_by LIMIT ? OFFSET ?";
$stmt_products = mysqli_prepare($conn, $sql_products);
mysqli_stmt_bind_param($stmt_products, "ii", $limit, $offset);
mysqli_stmt_execute($stmt_products);
$result_products = mysqli_stmt_get_result($stmt_products);
$products = $result_products ? mysqli_fetch_all($result_products, MYSQLI_ASSOC) : [];
?>

<?php include __DIR__ . '/../partials/header_category.php'; ?>

<main class="main-content">
    <section class="products-section">
        <div class="container">
            <div class="products-header">
                <h1><?php echo htmlspecialchars($page_title); ?></h1>
            </div>

            <div class="filter-bar">
                <div class="filter-controls">
                    <form method="GET" action="main.php" class="sort-select-wrapper">
                        <input type="hidden" name="r" value="category/women" />
                        <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
                        <select name="sort" class="sort-select" onchange="this.form.submit()">
                            <option value="newest" <?php echo ($sort == 'newest') ? 'selected' : ''; ?>>Mới nhất</option>
                            <option value="best-seller" <?php echo ($sort == 'best-seller') ? 'selected' : ''; ?>>Bán chạy nhất</option>
                            <option value="price-asc" <?php echo ($sort == 'price-asc') ? 'selected' : ''; ?>>Giá: Thấp → Cao</option>
                            <option value="price-desc" <?php echo ($sort == 'price-desc') ? 'selected' : ''; ?>>Giá: Cao → Thấp</option>
                        </select>
                        <div class="sort-select-arrow"><span class="material-symbols-outlined">expand_more</span></div>
                    </form>
                    <div class="filter-tags" id="filter-tags">
                        <a href="main.php?r=category/women" class="filter-tag-button <?php echo ($filter == '') ? 'active' : ''; ?>">Tất cả</a>
                        <a href="main.php?r=category/women&filter=hoa-co&sort=<?php echo $sort; ?>" class="filter-tag-button <?php echo ($filter == 'hoa-co') ? 'active' : ''; ?>">Hoa cỏ</a>
                        <a href="main.php?r=category/women&filter=ngot-ngao&sort=<?php echo $sort; ?>" class="filter-tag-button <?php echo ($filter == 'ngot-ngao') ? 'active' : ''; ?>">Ngọt ngào</a>
                        <a href="main.php?r=category/women&filter=phun&sort=<?php echo $sort; ?>" class="filter-tag-button <?php echo ($filter == 'phun') ? 'active' : ''; ?>">Phấn</a>
                        <a href="main.php?r=category/women&filter=trai-cay&sort=<?php echo $sort; ?>" class="filter-tag-button <?php echo ($filter == 'trai-cay') ? 'active' : ''; ?>">Trái cây</a>
                        <a href="main.php?r=category/women&filter=goi-cam&sort=<?php echo $sort; ?>" class="filter-tag-button <?php echo ($filter == 'goi-cam') ? 'active' : ''; ?>">Gợi cảm</a>
                    </div>
                </div>
                <div class="search-bar-wrapper">
                    <input class="search-bar-input" placeholder="Tìm trong Nước hoa Nữ..." type="search" />
                    <div class="search-bar-icon"><span class="material-symbols-outlined">search</span></div>
                </div>
            </div>

            <div class="product-grid">
                <?php if (empty($products)): ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                        <p style="color: white; font-size: 1.2rem;">Không tìm thấy sản phẩm nào phù hợp.</p>
                        <a href="main.php?r=category/women" style="color: var(--color-primary); text-decoration: underline; margin-top: 10px; display: inline-block;">Xem tất cả sản phẩm</a>
                    </div>
                <?php else: foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="card-image-wrapper">
                        <div class="card-image" data-bg="<?php echo htmlspecialchars(asset($product['hinh_anh'] ?? 'img/placeholder.png')); ?>"></div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?></h3>
                        <p class="card-price"><?php echo number_format($product['gia_thap_nhat']); ?> VND</p>
                        <div class="card-tags"><span class="card-tag">Thanh lịch</span><span class="card-tag">Quyến rũ</span></div>
                        <div class="card-rating"><span class="material-symbols-outlined">star</span><span class="material-symbols-outlined">star</span><span class="material-symbols-outlined">star</span><span class="material-symbols-outlined">star</span><span class="material-symbols-outlined">star</span></div>
                        <button type="button"
                                class="btn-add-to-cart"
                                onclick="addToCart(this)"
                                data-id="<?php echo (int)$product['ma_nuoc_hoa']; ?>"
                                data-name="<?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?>"
                                data-price="<?php echo (int)$product['gia_thap_nhat']; ?>"
                                data-image="<?php echo htmlspecialchars(asset($product['hinh_anh'] ?? 'img/placeholder.png')); ?>">
                            Thêm vào giỏ hàng
                        </button>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="pagination-nav">
                <?php if ($page > 1): ?>
                    <a href="main.php?r=category/women&page=<?php echo ($page - 1); ?>&filter=<?php echo $filter; ?>&sort=<?php echo $sort; ?>" class="pagination-arrow"><span class="material-symbols-outlined">chevron_left</span></a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="main.php?r=category/women&page=<?php echo $i; ?>&filter=<?php echo $filter; ?>&sort=<?php echo $sort; ?>" class="pagination-number <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="main.php?r=category/women&page=<?php echo ($page + 1); ?>&filter=<?php echo $filter; ?>&sort=<?php echo $sort; ?>" class="pagination-arrow"><span class="material-symbols-outlined">chevron_right</span></a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="why-saphira-section">
        <div class="container-small">
            <h2 class="section-title-alt">Tại sao chọn Nước hoa Nữ SAPHIRA?</h2>
            <div class="feature-grid">
                <div class="feature-item"><div class="feature-icon"><span class="material-symbols-outlined">spa</span></div><h3 class="feature-title">Hương thơm quyến rũ vượt thời gian</h3><p class="feature-text">Mỗi mùi hương là một tác phẩm nghệ thuật.</p></div>
                <div class="feature-item"><div class="feature-icon"><span class="material-symbols-outlined">verified</span></div><h3 class="feature-title">Thành phần cao cấp, an toàn cho da</h3><p class="feature-text">Lựa chọn nguyên liệu tinh túy.</p></div>
                <div class="feature-item"><div class="feature-icon"><span class="material-symbols-outlined">diamond</span></div><h3 class="feature-title">Bộ sưu tập đa dạng</h3><p class="feature-text">Tôn vinh vẻ đẹp phái nữ.</p></div>
            </div>
        </div>
    </section>

    <section class="review-section-men">
        <div class="container-small">
            <h2 class="section-title-alt-light">Khách hàng nói gì về SAPHIRA</h2>
            <div class="review-grid-men"></div>
        </div>
    </section>
    
</main>

<?php include __DIR__ . '/../partials/footer_category.php'; ?>
<?php mysqli_close($conn); ?>
