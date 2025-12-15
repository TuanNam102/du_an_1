<?php
session_start();
include 'config/db_connect.php';
include_once 'config/helpers.php';

if (!isset($_GET['slug'])) {
    header('Location: main.php?r=home');
    exit();
}
$slug = $_GET['slug'];

$stmt_product = mysqli_prepare($conn, "SELECT n.*, t.ten_thuong_hieu, dm.ten_danh_muc FROM nuoc_hoa n LEFT JOIN thuong_hieu t ON n.ma_thuong_hieu = t.ma_thuong_hieu LEFT JOIN danh_muc dm ON n.ma_danh_muc = dm.ma_danh_muc WHERE n.duong_dan = ?");
mysqli_stmt_bind_param($stmt_product, "s", $slug);
mysqli_stmt_execute($stmt_product);
$result_product = mysqli_stmt_get_result($stmt_product);
$product = $result_product ? mysqli_fetch_assoc($result_product) : null;

if (!$product) {
    die("Sản phẩm không tồn tại.");
}
$product_id = $product['ma_nuoc_hoa'];

$stmt_images = mysqli_prepare($conn, "SELECT duong_dan_hinh FROM hinh_anh_nuoc_hoa WHERE ma_nuoc_hoa = ? ORDER BY thu_tu ASC");
mysqli_stmt_bind_param($stmt_images, "i", $product_id);
mysqli_stmt_execute($stmt_images);
$result_images = mysqli_stmt_get_result($stmt_images);
$images = $result_images ? mysqli_fetch_all($result_images, MYSQLI_ASSOC) : [];
if (empty($images)) {
    $images[] = ['duong_dan_hinh' => '/img/cedrat-boise.jpg'];
}

$stmt_variants = mysqli_prepare($conn, "SELECT ma_bien_the, dung_tich_ml, gia_niem_yet, so_luong_ton FROM bien_the_nuoc_hoa WHERE ma_nuoc_hoa = ? ORDER BY dung_tich_ml ASC");
mysqli_stmt_bind_param($stmt_variants, "i", $product_id);
mysqli_stmt_execute($stmt_variants);
$result_variants = mysqli_stmt_get_result($stmt_variants);
$variants = $result_variants ? mysqli_fetch_all($result_variants, MYSQLI_ASSOC) : [];

$stmt_notes = mysqli_prepare($conn, "SELECT th.ten_huong, lk.loai_huong FROM nuoc_hoa_tang_huong lk JOIN tang_huong th ON lk.ma_tang_huong = th.ma_tang_huong WHERE lk.ma_nuoc_hoa = ?");
mysqli_stmt_bind_param($stmt_notes, "i", $product_id);
mysqli_stmt_execute($stmt_notes);
$result_notes = mysqli_stmt_get_result($stmt_notes);
$notes = ['huong_dau' => [], 'huong_giua' => [], 'huong_co' => []];
while ($result_notes && ($row = mysqli_fetch_assoc($result_notes))) {
    $notes[$row['loai_huong']][] = $row['ten_huong'];
}

$cat_id = $product['ma_danh_muc'];
$stmt_related = mysqli_prepare($conn, "SELECT n.ma_nuoc_hoa, n.ten_nuoc_hoa, n.duong_dan, MIN(b.gia_niem_yet) as gia_thap_nhat, MIN(h.duong_dan_hinh) as hinh_anh FROM nuoc_hoa n JOIN bien_the_nuoc_hoa b ON n.ma_nuoc_hoa = b.ma_nuoc_hoa LEFT JOIN hinh_anh_nuoc_hoa h ON n.ma_nuoc_hoa = h.ma_nuoc_hoa WHERE n.ma_danh_muc = ? AND n.ma_nuoc_hoa != ? AND (h.loai_hinh_anh = 'anh_lon' OR h.loai_hinh_anh IS NULL) GROUP BY n.ma_nuoc_hoa LIMIT 4");
mysqli_stmt_bind_param($stmt_related, "ii", $cat_id, $product_id);
mysqli_stmt_execute($stmt_related);
$result_related = mysqli_stmt_get_result($stmt_related);
$related_products = $result_related ? mysqli_fetch_all($result_related, MYSQLI_ASSOC) : [];

$page_title = $product['ten_nuoc_hoa'];
?>

<?php include __DIR__ . '/../partials/header.php'; ?>

<link rel="stylesheet" href="<?php echo asset('css/style-product-detail.css'); ?>" />
<style>
    .reveal {
        opacity: 0;
        transform: translateY(12px);
        transition: opacity .6s ease, transform .6s ease
    }

    .reveal.in {
        opacity: 1;
        transform: translateY(0)
    }
</style>

<div class="page-wrapper">
    <main class="product-detail-main">
        <div class="product-detail-container">
            <div class="product-layout-grid reveal">
                <div class="image-gallery">
                    <div class="main-image-wrapper">
                        <img id="main-product-image" class="main-image"
                            src="<?php echo asset($images[0]['duong_dan_hinh']); ?>"
                            alt="<?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?>" />
                    </div>
                    <div class="thumbnail-grid" id="thumbnail-grid">
                        <?php foreach ($images as $index => $img): ?>
                            <button class="thumbnail-button <?php echo ($index == 0) ? 'active' : ''; ?>"
                                onclick="changeImage(this, '<?php echo asset($img['duong_dan_hinh']); ?>')">
                                <img class="thumbnail-image" src="<?php echo asset($img['duong_dan_hinh']); ?>"
                                    alt="Thumb" />
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="product-info-container reveal">
                    <h1 class="product-title"><?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?></h1>
                    <p class="product-description"><?php echo htmlspecialchars($product['mo_ta_ngan']); ?></p>
                    <div class="product-notes">
                        <?php if (!empty($notes['huong_dau'])): ?>
                            <div>
                                <h3 class="notes-title">Nốt hương đầu (Top notes)</h3>
                                <p class="notes-text"><?php echo implode(', ', $notes['huong_dau']); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($notes['huong_giua'])): ?>
                            <div>
                                <h3 class="notes-title">Nốt hương giữa (Middle notes)</h3>
                                <p class="notes-text"><?php echo implode(', ', $notes['huong_giua']); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($notes['huong_co'])): ?>
                            <div>
                                <h3 class="notes-title">Nốt hương cuối (Base notes)</h3>
                                <p class="notes-text"><?php echo implode(', ', $notes['huong_co']); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (empty($notes['huong_dau']) && empty($notes['huong_giua']) && empty($notes['huong_co'])): ?>
                            <div class="notes-text" style="margin-top:10px; line-height:1.6;">
                                <?php echo nl2br(htmlspecialchars($product['mo_ta_chi_tiet'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="product-options">
                        <form action="main.php?r=cart/add" method="POST" id="addToCartForm">
                            <input type="hidden" name="product_id" value="<?php echo $product['ma_nuoc_hoa']; ?>">
                            <?php
                            $default_idx = -1;
                            $cheapest_idx = -1;
                            $min_price = PHP_INT_MAX;
                            foreach ($variants as $idx => $v) {
                                if ($v['so_luong_ton'] > 0) {
                                    if ($default_idx === -1)
                                        $default_idx = $idx;
                                    if ($v['gia_niem_yet'] < $min_price) {
                                        $min_price = $v['gia_niem_yet'];
                                        $cheapest_idx = $idx;
                                    }
                                }
                            }
                            if ($cheapest_idx !== -1) {
                                $default_idx = $cheapest_idx;
                            }
                            ?>
                            <input type="hidden" name="variant_id" id="variantIdHidden"
                                value="<?php echo ($default_idx >= 0) ? $variants[$default_idx]['ma_bien_the'] : 0; ?>" />
                            <fieldset class="size-selector">
                                <legend>Dung tích</legend>
                                <div class="variant-group" id="variantGroup">
                                    <?php foreach ($variants as $index => $variant): ?>
                                        <?php
                                        $disabled = ($variant['so_luong_ton'] <= 0);
                                        $status_class = 'status-ok';
                                        if ($disabled) {
                                            $status_class = 'status-out';
                                        } else if ($variant['so_luong_ton'] <= 5) {
                                            $status_class = 'status-low';
                                        }
                                        $is_active = ($index == $default_idx) && !$disabled;
                                        ?>
                                        <button type="button" class="variant-btn<?php echo $is_active ? ' active' : ''; ?>"
                                            data-id="<?php echo $variant['ma_bien_the']; ?>"
                                            data-price="<?php echo $variant['gia_niem_yet']; ?>"
                                            data-disabled="<?php echo $disabled ? '1' : '0'; ?>" <?php echo $disabled ? 'disabled' : ''; ?>>
                                            <span class="ml"><?php echo $variant['dung_tich_ml']; ?>ml</span>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </fieldset>

                            <p class="product-price" id="productPrice">
                                <?php echo ($default_idx >= 0) ? number_format($variants[$default_idx]['gia_niem_yet']) : 0; ?>
                                VND
                            </p>
                            <div class="product-actions">
                                <button type="button" class="btn-primary"
                                    onclick="alert('Chức năng Mua ngay đang cập nhật')">Mua Ngay</button>
                                <button type="button" class="btn-secondary" onclick="addToCart(this)"
                                    data-id="<?php echo (int) $product['ma_nuoc_hoa']; ?>"
                                    data-variant="<?php echo ($default_idx >= 0) ? (int) $variants[$default_idx]['ma_bien_the'] : 0; ?>"
                                    data-name="<?php echo htmlspecialchars($product['ten_nuoc_hoa']); ?>"
                                    data-price="<?php echo ($default_idx >= 0) ? (int) $variants[$default_idx]['gia_niem_yet'] : 0; ?>"
                                    data-image="<?php echo htmlspecialchars(asset($images[0]['duong_dan_hinh'])); ?>"
                                    data-volume="<?php echo ($default_idx >= 0) ? (int) $variants[$default_idx]['dung_tich_ml'] : 0; ?>">
                                    Thêm vào giỏ hàng
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="reviews-section">
                <div class="reviews-header">
                    <p class="section-title">Đánh giá từ khách hàng</p>
                    <p class="section-subtitle">Những chia sẻ chân thực từ những người đã trải nghiệm hương thơm của
                        chúng tôi.</p>
                </div>
                <div class="reviews-grid">
                    <div class="review-card reveal">
                        <div class="review-author"><img class="review-avatar"
                                src="https://randomuser.me/api/portraits/women/44.jpg" alt="Avatar" />
                            <div>
                                <p>Minh Anh</p>
                                <div class="review-stars"><span class="material-symbols-outlined">star</span><span
                                        class="material-symbols-outlined">star</span><span
                                        class="material-symbols-outlined">star</span><span
                                        class="material-symbols-outlined">star</span><span
                                        class="material-symbols-outlined">star</span></div>
                            </div>
                        </div>
                        <p class="review-text">"Mùi hương thực sự đẳng cấp. Tinh tế, sang trọng và lưu hương rất lâu."
                        </p>
                    </div>
                    <div class="review-card reveal">
                        <div class="review-author"><img class="review-avatar"
                                src="https://randomuser.me/api/portraits/men/32.jpg" alt="Avatar" />
                            <div>
                                <p>Quốc Bảo</p>
                                <div class="review-stars"><span class="material-symbols-outlined">star</span><span
                                        class="material-symbols-outlined">star</span><span
                                        class="material-symbols-outlined">star</span><span
                                        class="material-symbols-outlined">star</span><span
                                        class="material-symbols-outlined">star_half</span></div>
                            </div>
                        </div>
                        <p class="review-text">"Một mùi hương rất đặc biệt, không thể nhầm lẫn. Rất đáng đồng tiền."</p>
                    </div>
                </div>
            </div>

            <div class="related-section">
                <div class="related-header">
                    <p class="section-title">Sản phẩm tương tự</p>
                </div>
                <div class="related-grid">
                    <?php foreach ($related_products as $rel): ?>
                        <div class="related-product-card reveal">
                            <div class="related-image-wrapper"><img class="related-image"
                                    src="public<?php echo $rel['hinh_anh']; ?>"
                                    alt="<?php echo htmlspecialchars($rel['ten_nuoc_hoa']); ?>" /></div>
                            <div class="related-info">
                                <p class="related-title"><?php echo htmlspecialchars($rel['ten_nuoc_hoa']); ?></p>
                                <p class="related-price"><?php echo number_format($rel['gia_thap_nhat']); ?> VND</p><a
                                    href="main.php?r=product/details&slug=<?php echo $rel['duong_dan']; ?>" class="btn-sm"
                                    style="text-decoration: none; display: inline-block;">Xem Chi tiết</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/../partials/footer.php'; ?>
</div>

<script>
    function changeImage(element, src) {
        const mainImg = document.getElementById('main-product-image');
        if (mainImg) mainImg.src = src;
        const buttons = document.querySelectorAll('.thumbnail-button');
        buttons.forEach(btn => { btn.classList.remove('active'); });
        element.classList.add('active');
    }
    function selectVariant(btn) {
        const price = btn.getAttribute('data-price');
        const id = btn.getAttribute('data-id');
        const formattedPrice = new Intl.NumberFormat('vi-VN').format(price);
        const el = document.getElementById('productPrice');
        el.innerText = formattedPrice + ' VND';
        el.classList.remove('pulse');
        void el.offsetWidth;
        el.classList.add('pulse');
        document.getElementById('variantIdHidden').value = id;
        document.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        btn.classList.remove('ripple');
        void btn.offsetWidth;
        btn.classList.add('ripple');
    }
    (function () {
        const group = document.getElementById('variantGroup');
        const btns = Array.from(group.querySelectorAll('.variant-btn'));
        btns.forEach(btn => {
            btn.addEventListener('click', () => { if (!btn.disabled) selectVariant(btn); });
            btn.addEventListener('keydown', (e) => {
                const enabled = btns.filter(b => !b.disabled);
                const idx = enabled.indexOf(btn);
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    const next = enabled[(idx + 1) % enabled.length];
                    next.focus(); selectVariant(next); e.preventDefault();
                } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    const prev = enabled[(idx - 1 + enabled.length) % enabled.length];
                    prev.focus(); selectVariant(prev); e.preventDefault();
                } else if (e.key === 'Enter' || e.key === ' ') { selectVariant(btn); e.preventDefault(); }
            });
        });
    })();
    const observer = new IntersectionObserver(entries => { entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('in'); } }); }, { threshold: 0.15 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>

<?php mysqli_close($conn); ?>
<?php include_once 'config/helpers.php'; ?>
<script src="<?php echo asset('js/script.js'); ?>"></script>
</body>

</html>