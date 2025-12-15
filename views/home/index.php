<?php
session_start();
$active_page = 'index';

include __DIR__ . '/../../config/db_connect.php';
include __DIR__ . '/../../config/helpers.php';

$stmt_slider = mysqli_prepare($conn, "SELECT * FROM slider WHERE Active = 1");
mysqli_stmt_execute($stmt_slider);
$result_slider = mysqli_stmt_get_result($stmt_slider);
$sliders = $result_slider ? mysqli_fetch_all($result_slider, MYSQLI_ASSOC) : [];

$stmt_cats = mysqli_prepare($conn, "SELECT * FROM danh_muc LIMIT ?");
$limit_cats = 4;
mysqli_stmt_bind_param($stmt_cats, "i", $limit_cats);
mysqli_stmt_execute($stmt_cats);
$result_cats = mysqli_stmt_get_result($stmt_cats);
$categories = $result_cats ? mysqli_fetch_all($result_cats, MYSQLI_ASSOC) : [];

$sql_sale = "
    SELECT 
        n.ma_nuoc_hoa, n.ten_nuoc_hoa, n.duong_dan, 
        n.gia_niem_yet, n.gia_khuyen_mai,
        n.mo_ta_ngan,
        MIN(h.duong_dan_hinh) as duong_dan_hinh
    FROM nuoc_hoa n
    LEFT JOIN hinh_anh_nuoc_hoa h ON n.ma_nuoc_hoa = h.ma_nuoc_hoa
    WHERE 
        n.gia_khuyen_mai < n.gia_niem_yet 
        AND n.gia_khuyen_mai > 0
        AND (h.loai_hinh_anh = 'anh_lon' OR h.loai_hinh_anh IS NULL)
    GROUP BY n.ma_nuoc_hoa, n.ten_nuoc_hoa, n.duong_dan, n.gia_niem_yet, n.gia_khuyen_mai, n.mo_ta_ngan
    ORDER BY (n.gia_niem_yet - n.gia_khuyen_mai) DESC 
    LIMIT 4
";
$stmt_sale = mysqli_prepare($conn, $sql_sale);
mysqli_stmt_execute($stmt_sale);
$result_sale = mysqli_stmt_get_result($stmt_sale);
$products_sale = $result_sale ? mysqli_fetch_all($result_sale, MYSQLI_ASSOC) : [];

$sql_new = "
    SELECT 
        n.ma_nuoc_hoa, n.ten_nuoc_hoa, n.duong_dan, 
        n.gia_niem_yet, n.gia_khuyen_mai,
        n.mo_ta_ngan,
        MIN(h.duong_dan_hinh) as duong_dan_hinh
    FROM nuoc_hoa n
    LEFT JOIN hinh_anh_nuoc_hoa h ON n.ma_nuoc_hoa = h.ma_nuoc_hoa
    WHERE (h.loai_hinh_anh = 'anh_lon' OR h.loai_hinh_anh IS NULL)
    GROUP BY n.ma_nuoc_hoa, n.ten_nuoc_hoa, n.duong_dan, n.gia_niem_yet, n.gia_khuyen_mai, n.mo_ta_ngan
    ORDER BY n.thoi_gian_tao DESC 
    LIMIT 4
";
$stmt_new = mysqli_prepare($conn, $sql_new);
mysqli_stmt_execute($stmt_new);
$result_new = mysqli_stmt_get_result($stmt_new);
$products_new = $result_new ? mysqli_fetch_all($result_new, MYSQLI_ASSOC) : [];

$sql_reviews = "
    SELECT dg.noi_dung, dg.so_sao, nd.ho_ten, nd.anh_dai_dien
    FROM danh_gia dg
    JOIN nguoi_dung nd ON dg.ma_nguoi_dung = nd.ma_nguoi_dung
    WHERE dg.trang_thai = 1 AND dg.so_sao >= 4
    ORDER BY dg.thoi_gian_tao DESC
    LIMIT 3
";
$stmt_reviews = mysqli_prepare($conn, $sql_reviews);
mysqli_stmt_execute($stmt_reviews);
$result_reviews = mysqli_stmt_get_result($stmt_reviews);
$reviews = $result_reviews ? mysqli_fetch_all($result_reviews, MYSQLI_ASSOC) : [];

$sql_best = "SELECT n.ma_nuoc_hoa, n.ten_nuoc_hoa, n.duong_dan, COALESCE(NULLIF(n.gia_khuyen_mai,0), n.gia_niem_yet) as gia_hien_tai, n.mo_ta_ngan, MIN(h.duong_dan_hinh) as duong_dan_hinh, SUM(ct.so_luong) as tong_ban FROM chi_tiet_don_hang ct JOIN don_hang dh ON ct.ma_don_hang = dh.ma_don_hang JOIN bien_the_nuoc_hoa b ON ct.ma_bien_the = b.ma_bien_the JOIN nuoc_hoa n ON b.ma_nuoc_hoa = n.ma_nuoc_hoa LEFT JOIN hinh_anh_nuoc_hoa h ON n.ma_nuoc_hoa = h.ma_nuoc_hoa WHERE (dh.trang_thai_don <> 'da_huy') AND (h.loai_hinh_anh = 'anh_lon' OR h.loai_hinh_anh IS NULL) GROUP BY n.ma_nuoc_hoa, n.ten_nuoc_hoa, n.duong_dan, n.gia_niem_yet, n.gia_khuyen_mai, n.mo_ta_ngan ORDER BY tong_ban DESC LIMIT 4";
$stmt_best = mysqli_prepare($conn, $sql_best);
mysqli_stmt_execute($stmt_best);
$res_best = mysqli_stmt_get_result($stmt_best);
$products_best = $res_best ? mysqli_fetch_all($res_best, MYSQLI_ASSOC) : [];
?>

<?php include __DIR__ . '/../partials/header.php'; ?>

<main>
<?php /* Phần nội dung giữ nguyên từ file index.php cũ */ ?>
<?php /* Bắt đầu dán nội dung HTML/JS */ ?>
<section class="hero-section">
        <div class="hero-wrapper" id="heroWrapper">
            <?php 
            if (empty($sliders)) {
                $sliders = [
                    [
                        'SliderName' => 'Bộ Sưu Tập Độc Quyền',
                        'Thumbnail' => 'img/banner/slideshow.jpg',
                        'Subtitle' => 'Tinh hoa hương thơm đẳng cấp thế giới'
                    ]
                ];
            }

            foreach ($sliders as $index => $slide): 
                $isActive = ($index === 0) ? 'active' : '';
                $imgUrl = $slide['Thumbnail'];
                if (!filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                    $imgUrl = asset($imgUrl);
                }
                if(empty($sliders) && !isset($slide['Active'])) {
                     $imgUrl = 'https://images.unsplash.com/photo-1595425970375-3d59d1e6ec39?auto=format&fit=crop&w=2070&q=80';
                }
            ?>
                <div class="hero-slide <?php echo $isActive; ?>" data-index="<?php echo $index; ?>">
                    <div class="hero-background" style="background-image: url('<?php echo $imgUrl; ?>');"></div>
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <h1 class="hero-title"><?php echo htmlspecialchars($slide['SliderName']); ?></h1>
                        <p class="hero-subtitle">Khám phá vẻ đẹp tiềm ẩn qua từng tầng hương tinh tế. Đẳng cấp tạo nên sự khác biệt.</p>
                        <div>
                            <a href="main.php?r=category/men" class="btn btn-primary">Khám Phá Ngay</a>
                            <a href="main.php?r=category/women" class="btn btn-outline">Mua Sắm</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="slider-arrow left" id="prevSlide">
            <span class="material-symbols-outlined">arrow_back_ios_new</span>
        </div>
        <div class="slider-arrow right" id="nextSlide">
            <span class="material-symbols-outlined">arrow_forward_ios</span>
        </div>

        <div class="slider-dots" id="heroDots">
            <?php foreach ($sliders as $index => $slide): ?>
                <div class="slider-dot <?php echo ($index === 0) ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="category-section section-padding bg-dark">
        <div class="container">
            <h2 class="section-title text-light">Danh Mục Sản Phẩm</h2>
            <div class="grid grid-cols-4">
                <?php foreach ($categories as $cat): ?>
                <?php 
                    $link = 'main.php?r=category/men';
                    if (strpos($cat['duong_dan'], 'nu') !== false) $link = 'main.php?r=category/women';
                    elseif (strpos($cat['duong_dan'], 'unisex') !== false) $link = 'main.php?r=category/unisex';
                    $cat_img = !empty($cat['hinh_anh']) ? asset($cat['hinh_anh']) : asset('img/nautica-voyage-eau-de-toilette-100ml.jpg');
                ?>
                <a href="<?php echo $link; ?>" class="category-card group">
                    <div class="category-card-bg" style='background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0) 50%), url("<?php echo $cat_img; ?>");'>
                        <p class="category-card-title"><?php echo htmlspecialchars($cat['ten_danh_muc']); ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="product-section section-padding bg-dark-light">
        <div class="container">
            <h2 class="section-title text-light">SẢN PHẨM GIẢM GIÁ</h2>
            <div class="grid grid-cols-4">
                <?php foreach ($products_sale as $product): ?>
                <div class="product-card group">
                    <div class="product-image-wrapper">
                        <div class="sale-badge">GIẢM GIÁ</div>
                        <a href="main.php?r=product/details&slug=<?php echo $product['duong_dan']; ?>" style="display: block; height: 100%;">
                            <div class="product-image" data-bg="<?php echo htmlspecialchars(asset($product['duong_dan_hinh'] ?? 'img/placeholder.png')); ?>"></div>
                        </a>
                        <div class="product-hover-overlay"></div>
                        <div class="product-hover-border"></div>
                        <div class="product-actions">
                            <a href="main.php?r=product/details&slug=<?php echo $product['duong_dan']; ?>" class="product-action-btn" title="Xem nhanh">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                            <button type="button" class="product-action-btn" title="Thêm vào giỏ hàng"
                                    onclick="addToCart(this)"
                                    data-id="<?php echo isset($product['ma_nuoc_hoa']) ? (int)$product['ma_nuoc_hoa'] : 0; ?>"
                                    data-name="<?php echo isset($product['ten_nuoc_hoa']) ? htmlspecialchars($product['ten_nuoc_hoa']) : ''; ?>"
                                    data-price="<?php echo isset($product['gia_thap_nhat']) ? (int)$product['gia_thap_nhat'] : (isset($product['gia_khuyen_mai']) ? (int)$product['gia_khuyen_mai'] : (isset($product['gia_niem_yet']) ? (int)$product['gia_niem_yet'] : 0)); ?>"
                                    data-image="<?php echo htmlspecialchars(asset(isset($product['duong_dan_hinh']) ? $product['duong_dan_hinh'] : 'img/placeholder.png')); ?>">
                                <span class="material-symbols-outlined">shopping_cart</span>
                            </button>
                            <a href="#" class="product-action-btn" title="Yêu thích">
                                <span class="material-symbols-outlined">favorite</span>
                            </a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">
                            <a href="main.php?r=product/details&slug=<?php echo $product['duong_dan']; ?>" class="text-light" style="text-decoration: none;">
                                <?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?>
                            </a>
                        </h3>
                        <p class="product-category text-gray">
                            <?php echo htmlspecialchars(mb_strimwidth($product['mo_ta_ngan'], 0, 30, "...")); ?>
                        </p> 
                        <p class="product-price text-gray">
                            <span class="line-through"><?php echo number_format($product['gia_niem_yet']); ?>₫</span> 
                            <span class="text-primary"><?php echo number_format($product['gia_khuyen_mai']); ?>₫</span>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="product-section section-padding bg-dark">
        <div class="container">
            <h2 class="section-title text-light">SẢN PHẨM MỚI VỀ</h2>
            <div class="grid grid-cols-4">
                <?php foreach ($products_new as $product): ?>
                <div class="product-card-alt">
                    <div class="product-image-wrapper-alt">
                        <div class="best-seller-badge">NEW</div>
                        <div class="product-image-alt" data-bg="<?php echo htmlspecialchars(asset($product['duong_dan_hinh'] ?? 'img/placeholder.png')); ?>"></div>
                    </div>
                    <div class="product-info-alt">
                        <p class="product-name text-light"><?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?></p>
                        <p class="product-category text-gray-light">
                            <?php echo htmlspecialchars(mb_strimwidth($product['mo_ta_ngan'], 0, 40, "...")); ?>
                        </p>
                        <p class="product-price-alt"><?php echo number_format($product['gia_niem_yet']); ?>₫</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="product-section section-padding bg-dark">
        <div class="container">
            <h2 class="section-title text-light">SẢN PHẨM BÁN CHẠY</h2>
            <div class="grid grid-cols-4">
                <?php foreach ($products_best as $product): ?>
                <div class="product-card-alt">
                    <div class="product-image-wrapper-alt">
                        <div class="best-seller-badge">BEST</div>
                        <div class="product-image-alt" data-bg="<?php echo htmlspecialchars(asset($product['duong_dan_hinh'] ?? 'img/placeholder.png')); ?>"></div>
                    </div>
                    <div class="product-info-alt">
                        <p class="product-name text-light"><?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?></p>
                        <p class="product-category text-gray-light"><?php echo htmlspecialchars(mb_strimwidth($product['mo_ta_ngan'], 0, 40, "...")); ?></p>
                        <p class="product-price-alt"><?php echo number_format($product['gia_hien_tai']); ?>₫</p>
                        <a href="main.php?r=product/details&slug=<?php echo $product['duong_dan']; ?>" class="btn-sm" style="text-decoration: none; display: inline-block;">Xem Chi tiết</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="news-events-section">
       <div class="container">
            <h2 class="section-title text-light">Tin Tức & Sự Kiện SAPHIRA</h2>
            <div class="news-events-tabs">
                <button class="news-tab active" data-category="all">Tất Cả</button>
                <button class="news-tab" data-category="events">Sự Kiện</button>
                <button class="news-tab" data-category="news">Tin Tức</button>
                <button class="news-tab" data-category="blog">Blog</button>
            </div>
            <div class="news-grid">
                <div class="news-card" data-category="events">
                    <div class="news-image" style="background-image: url('https://images.unsplash.com/photo-1540574163026-643ea20ade25?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">
                        <div class="news-date"><div class="day">15</div><div class="month">Th12</div></div>
                    </div>
                    <div class="news-content">
                        <div class="news-category">Sự Kiện Ra Mắt</div>
                        <h3 class="news-title">Đêm Hội Hương Thơm: Giới Thiệu Bộ Sưu Tập Mùa Đông 2024</h3>
                        <div class="news-meta"><a href="#" class="news-read-more">Đọc thêm →</a></div>
                    </div>
                </div>
                <div class="news-card" data-category="news">
                    <div class="news-image" style="background-image: url('https://images.unsplash.com/photo-1595425970375-3d59d1e6ec39?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">
                        <div class="news-date"><div class="day">08</div><div class="month">Th12</div></div>
                    </div>
                    <div class="news-content">
                        <div class="news-category">Tin Tức</div>
                        <h3 class="news-title">SAPHIRA Đạt Giải Thưởng "Nước Hoa Sang Trọng Nhất 2024"</h3>
                        <div class="news-meta"><a href="#" class="news-read-more">Đọc thêm →</a></div>
                    </div>
                </div>
                <div class="news-card" data-category="blog">
                    <div class="news-image" style="background-image: url('https://images.unsplash.com/photo-1544468266-6a8948001c78?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">
                        <div class="news-date"><div class="day">01</div><div class="month">Th12</div></div>
                    </div>
                    <div class="news-content">
                        <div class="news-category">Blog Hương Thơm</div>
                        <h3 class="news-title">Nghệ Thuật Phối Hương: Cách Tạo Ra Một Mùi Hương Độc Đáo</h3>
                        <div class="news-meta"><a href="#" class="news-read-more">Đọc thêm →</a></div>
                    </div>
                </div>
            </div>
       </div>
    </section>

    <section class="review-section section-padding bg-dark-light">
        <div class="container-small">
            <h2 class="section-title text-light">Khách Hàng Nói Gì</h2>
            <div class="grid grid-cols-3">
                <?php if(empty($reviews)): ?>
                    <div class="review-card">
                        <img alt="Avatar" class="review-avatar" src="https://randomuser.me/api/portraits/women/44.jpg"/>
                        <div class="review-stars"><span class="material-symbols-outlined">star</span><span class="material-symbols-outlined">star</span><span class="material-symbols-outlined">star</span><span class="material-symbols-outlined">star</span><span class="material-symbols-outlined">star</span></div>
                        <p class="review-text">"Tuyệt đẹp. Aura of Elegance là mùi hương đặc trưng mới của tôi."</p>
                        <p class="review-author">- Jessica L.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <?php 
                            $avatar = !empty($review['anh_dai_dien']) ? asset($review['anh_dai_dien']) : "https://randomuser.me/api/portraits/men/32.jpg"; 
                        ?>
                        <img alt="Avatar" class="review-avatar" src="<?php echo $avatar; ?>"/>
                        <div class="review-stars">
                            <?php for($i=0; $i<$review['so_sao']; $i++): ?>
                                <span class="material-symbols-outlined">star</span>
                            <?php endfor; ?>
                        </div>
                        <p class="review-text">"<?php echo htmlspecialchars($review['noi_dung']); ?>"</p>
                        <p class="review-author">- <?php echo htmlspecialchars($review['ho_ten']); ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

</main>
<!-- Slider logic is handled by public/js/script.js -->

<?php include __DIR__ . '/../partials/footer.php'; ?>
<?php mysqli_close($conn); ?>
