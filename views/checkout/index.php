<?php
session_start();
include 'config/db_connect.php';
include __DIR__ . '/../../admin/csrf.php';
include_once 'config/helpers.php';
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'main.php?r=checkout';
    header('Location: main.php?r=login');
    exit();
}
$user_id = $_SESSION['user_id'];
$cart_items = [];
$total_price = 0;
$error_message = '';
$stmt_user = mysqli_prepare($conn, "SELECT ho_ten, so_dien_thoai, email FROM nguoi_dung WHERE ma_nguoi_dung = ?");
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$res_user = mysqli_stmt_get_result($stmt_user);
$user_info = $res_user ? mysqli_fetch_assoc($res_user) : null;
$stmt_address = mysqli_prepare($conn, "SELECT * FROM dia_chi_giao_hang WHERE ma_nguoi_dung = ? ORDER BY mac_dinh DESC, ma_dia_chi DESC");
mysqli_stmt_bind_param($stmt_address, "i", $user_id);
mysqli_stmt_execute($stmt_address);
$res_address = mysqli_stmt_get_result($stmt_address);
$address_list = $res_address ? mysqli_fetch_all($res_address, MYSQLI_ASSOC) : [];
$user_address = $address_list[0] ?? null;
$fill_name = $user_address['ho_ten_nguoi_nhan'] ?? ($user_info['ho_ten'] ?? '');
$fill_phone = $user_address['so_dien_thoai'] ?? ($user_info['so_dien_thoai'] ?? '');
$fill_address = $user_address['dia_chi_chi_tiet'] ?? '';
$stmt_cart = mysqli_prepare($conn, "SELECT ma_gio_hang FROM gio_hang WHERE ma_nguoi_dung = ?");
mysqli_stmt_bind_param($stmt_cart, "i", $user_id);
mysqli_stmt_execute($stmt_cart);
$res_cart = mysqli_stmt_get_result($stmt_cart);
if (mysqli_num_rows($res_cart) > 0) {
    $cart = mysqli_fetch_assoc($res_cart);
    $cart_id = $cart['ma_gio_hang'];
    $sql_items = "SELECT mh.ma_mat_hang, mh.so_luong, mh.gia, mh.ma_bien_the, b.dung_tich_ml, n.ten_nuoc_hoa, MIN(h.duong_dan_hinh) as hinh_anh FROM mat_hang_gio_hang mh JOIN bien_the_nuoc_hoa b ON mh.ma_bien_the = b.ma_bien_the JOIN nuoc_hoa n ON b.ma_nuoc_hoa = n.ma_nuoc_hoa LEFT JOIN hinh_anh_nuoc_hoa h ON n.ma_nuoc_hoa = h.ma_nuoc_hoa WHERE mh.ma_gio_hang = ? AND (h.loai_hinh_anh = 'anh_lon' OR h.loai_hinh_anh IS NULL) GROUP BY mh.ma_mat_hang";
    $stmt_items = mysqli_prepare($conn, $sql_items);
    mysqli_stmt_bind_param($stmt_items, "i", $cart_id);
    mysqli_stmt_execute($stmt_items);
    $res_items = mysqli_stmt_get_result($stmt_items);
    $cart_items = $res_items ? mysqli_fetch_all($res_items, MYSQLI_ASSOC) : [];
    foreach ($cart_items as $item) {
        $total_price += ($item['gia'] * $item['so_luong']);
    }
}
$shipping_fee = 0;
$grand_total = $total_price + $shipping_fee;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_cart = [];
    if (isset($_POST['cart_json']) && $_POST['cart_json'] !== '') {
        $decoded = json_decode($_POST['cart_json'], true);
        if (is_array($decoded)) {
            $client_cart = $decoded;
        }
    }
    if (!csrf_verify($_POST['_csrf'] ?? null)) {
        $error_message = 'Phiên không hợp lệ, vui lòng thử lại.';
    } else if (empty($client_cart)) {
        $error_message = 'Giỏ hàng trống!';
    } else {
        $selected_address_id = isset($_POST['address_id']) ? (int) $_POST['address_id'] : 0;
        $note = $_POST['note'] ?? '';
        $payment_method = (int) $_POST['payment_method'];
        mysqli_begin_transaction($conn);
        try {
            $validated_items = [];
            $grand_total = 0;
            foreach ($client_cart as $ci) {
                $variant_id = isset($ci['variant_id']) ? (int) $ci['variant_id'] : 0;
                $qty = isset($ci['quantity']) ? (int) $ci['quantity'] : 0;
                if ($variant_id <= 0) {
                    throw new Exception('Vui lòng chọn dung tích cho mọi sản phẩm trước khi đặt.');
                }
                if ($qty <= 0) {
                    $qty = 1;
                }
                $stmt_v = mysqli_prepare($conn, "SELECT b.ma_bien_the, b.dung_tich_ml, b.gia_niem_yet, n.ten_nuoc_hoa FROM bien_the_nuoc_hoa b JOIN nuoc_hoa n ON b.ma_nuoc_hoa = n.ma_nuoc_hoa WHERE b.ma_bien_the = ?");
                mysqli_stmt_bind_param($stmt_v, "i", $variant_id);
                mysqli_stmt_execute($stmt_v);
                $res_v = mysqli_stmt_get_result($stmt_v);
                $row_v = $res_v ? mysqli_fetch_assoc($res_v) : null;
                if (!$row_v) {
                    throw new Exception('Biến thể không hợp lệ: ' . $variant_id);
                }
                $price = (int) $row_v['gia_niem_yet'];
                $grand_total += $price * $qty;
                $validated_items[] = ['variant_id' => $variant_id, 'qty' => $qty, 'price' => $price, 'name' => $row_v['ten_nuoc_hoa']];
            }
            if ($selected_address_id <= 0) {
                throw new Exception('Vui lòng chọn một địa chỉ giao hàng.');
            }
            $stmt_addr_check = mysqli_prepare($conn, "SELECT ma_dia_chi FROM dia_chi_giao_hang WHERE ma_dia_chi = ? AND ma_nguoi_dung = ?");
            mysqli_stmt_bind_param($stmt_addr_check, "ii", $selected_address_id, $user_id);
            mysqli_stmt_execute($stmt_addr_check);
            $res_addr_check = mysqli_stmt_get_result($stmt_addr_check);
            if (!$res_addr_check || mysqli_num_rows($res_addr_check) === 0) {
                throw new Exception('Địa chỉ giao hàng không hợp lệ.');
            }
            $addr_id = $selected_address_id;
            $stmt_order = mysqli_prepare($conn, "INSERT INTO don_hang (ma_nguoi_dung, tong_tien, ma_dia_chi_giao_hang, ma_phuong_thuc_thanh_toan, ghi_chu, trang_thai_don) VALUES (?, ?, ?, ?, ?, 'cho_xac_nhan')");
            mysqli_stmt_bind_param($stmt_order, "iiiis", $user_id, $grand_total, $addr_id, $payment_method, $note);
            if (!mysqli_stmt_execute($stmt_order)) {
                throw new Exception('Không thể tạo đơn hàng: ' . mysqli_error($conn));
            }
            $order_id = mysqli_insert_id($conn);
            foreach ($validated_items as $vi) {
                $stmt_detail = mysqli_prepare($conn, "INSERT INTO chi_tiet_don_hang (ma_don_hang, ma_bien_the, so_luong, don_gia, ten_nuoc_hoa) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt_detail, "iiiis", $order_id, $vi['variant_id'], $vi['qty'], $vi['price'], $vi['name']);
                if (!mysqli_stmt_execute($stmt_detail)) {
                    throw new Exception('Không thể lưu chi tiết đơn: ' . mysqli_error($conn));
                }
            }
            mysqli_commit($conn);
            header("Location: main.php?r=order/detail&id=$order_id&clear=1");
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error_message = $e->getMessage();
        }
    }
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<style>
    :root { --font-display: 'Manrope', sans-serif; --font-serif: 'Cormorant Garamond', serif; --color-primary: #f4c025; --color-gold: #D4AF37; --color-bg-start: #1a1a1a; --color-bg-end: #000000; --color-header: rgba(0,0,0,0.8); --color-text-light: #ffffff; --color-text-dim: rgba(255,255,255,0.8); --color-text-dimmer: rgba(255,255,255,0.7); --color-text-placeholder: rgba(255,255,255,0.5); --color-border: rgba(255,255,255,0.2); --color-border-focus: rgba(212,175,55,0.5); --color-bg-input: rgba(255,255,255,0.05); --color-bg-input-hover: rgba(255,255,255,0.1); --color-bg-summary: rgba(0,0,0,0.5); --color-black: #000000; --border-radius: 0.5rem; --border-radius-lg: 0.75rem; --border-radius-xl: 1rem; }
    
    .checkout-main-container{flex:1;width:100%;max-width:1280px;margin:0 auto;padding:2.5rem 1rem} @media(min-width:768px){.checkout-main-container{padding:4rem 2rem}}
    .checkout-page-header{margin-bottom:2.5rem;text-align:center} .checkout-page-title{font-family:var(--font-serif);font-size:2.25rem;font-weight:700;color:var(--color-gold);letter-spacing:.025em} @media(min-width:768px){.checkout-page-title{font-size:3rem}} .checkout-page-subtitle{margin-top:.5rem;color:var(--color-text-dim);font-size:1rem} @media(min-width:768px){.checkout-page-subtitle{font-size:1.125rem}}
    .checkout-layout-grid{display:grid;grid-template-columns:1fr;gap:2rem} @media(min-width:1024px){.checkout-layout-grid{grid-template-columns:repeat(5,1fr);gap:3rem}} .checkout-form-column{grid-column:span 1;display:flex;flex-direction:column;gap:2rem} @media(min-width:1024px){.checkout-form-column{grid-column:span 3}}
    .form-section-title{font-family:var(--font-serif);font-size:1.5rem;font-weight:700;color:var(--color-gold);margin-bottom:1rem}
    .form-grid{display:grid;grid-template-columns:1fr;gap:1rem} @media(min-width:640px){.form-grid{grid-template-columns:repeat(2,1fr)}} .col-span-2{grid-column:span 1} @media(min-width:640px){.col-span-2{grid-column:span 2}}
    .form-label{color:var(--color-text-dim);font-size:.875rem;font-weight:500;padding-bottom:.5rem;display:block} .form-input,.form-select{display:flex;width:100%;min-width:0;flex:1;resize:none;overflow:hidden;border-radius:var(--border-radius-lg);color:var(--color-text-light);background-color:var(--color-bg-input);border:1px solid var(--color-border);height:3rem;padding:.75rem;font-size:1rem;transition:all .15s ease} .form-input::placeholder,.form-select{color:var(--color-text-placeholder)} .form-input:focus,.form-select:focus{outline:none;border-color:var(--color-gold);box-shadow:0 0 0 1px var(--color-border-focus)} .form-select{appearance:none}
    .radio-group{display:flex;flex-direction:column;gap:.75rem} .radio-option,.radio-option-header{display:flex;align-items:center;padding:1rem;border-radius:var(--border-radius-lg);background-color:var(--color-bg-input);border:1px solid var(--color-border);cursor:pointer;transition:all .15s ease} .radio-option:hover,.radio-option-header:hover{background-color:var(--color-bg-input-hover)} .form-radio{height:1.25rem;width:1.25rem;background-color:var(--color-bg-input-hover);border:1px solid var(--color-border);color:var(--color-gold)} .form-radio:focus{outline:none;box-shadow:0 0 0 1px var(--color-border-focus)} .radio-option-content{margin-left:1rem;flex:1} .radio-option-content p{font-weight:600;color:var(--color-text-light)} .radio-option-content span{font-size:.875rem;color:var(--color-text-dimmer)} .radio-option-price{font-weight:600;color:var(--color-text-light)} .radio-option span{margin-left:1rem;font-weight:600;color:var(--color-text-light)} .radio-option:has(:checked),.radio-option-collapsible:has(:checked){border-color:var(--color-gold);background-color:rgba(212,175,55,0.1)} .radio-option-collapsible{border-radius:var(--border-radius-lg);background-color:var(--color-bg-input);border:1px solid var(--color-border);transition:all .15s ease} .radio-option-body{padding:0 1rem 1rem 1rem;margin-top:.5rem;border-top:1px solid var(--color-border);display:none} .radio-option-collapsible:has(:checked) .radio-option-body{display:block}
    .checkout-summary-column{grid-column:span 1} @media(min-width:1024px){.checkout-summary-column{grid-column:span 2}} .summary-box{position:sticky;top:7rem;background-color:var(--color-bg-summary);padding:1.5rem;border-radius:var(--border-radius-xl);box-shadow:0 4px 10px rgba(0,0,0,.3);border:1px solid var(--color-border);backdrop-filter:blur(12px)} @media(min-width:768px){.summary-box{padding:2rem}} .summary-title{font-family:var(--font-serif);font-size:1.5rem;font-weight:700;color:var(--color-gold);margin-bottom:1.5rem;text-align:center}
    .summary-items-list{display:flex;flex-direction:column;gap:1rem} .summary-item{display:flex;align-items:center;gap:1rem} .summary-item-image{width:4rem;height:4rem;border-radius:var(--border-radius-lg);object-fit:cover;flex-shrink:0} .summary-item-info{flex:1} .summary-item-info p{font-weight:600;color:var(--color-text-light)} .summary-item-info span{font-size:.875rem;color:var(--color-text-dimmer)} .summary-item-price{font-weight:500;color:var(--color-text-light)} .summary-divider{border-top:1px solid var(--color-border);margin:1.5rem 0}
    .summary-costs{display:flex;flex-direction:column;gap:.75rem;font-size:.875rem} .summary-cost-row{display:flex;justify-content:space-between;color:var(--color-text-dim)} .voucher-row{display:grid;grid-template-columns:1fr auto;gap:.75rem;align-items:center} .voucher-input{height:3rem} .voucher-feedback{font-size:.875rem} .voucher-feedback.success{color:var(--color-gold)} .voucher-feedback.error{color:#ff6b6b}
    .summary-total{display:flex;justify-content:space-between;align-items:center;color:var(--color-text-light)} .summary-total span:first-child{font-size:1.125rem;font-weight:600} .total-price{font-size:1.5rem;font-weight:700;color:var(--color-gold)} .form-checkbox-label.terms{margin-top:2rem} .form-checkbox-label.terms a{color:var(--color-gold)} .form-checkbox-label.terms a:hover{text-decoration:underline}
    .btn-place-order{margin-top:1.5rem;width:100%;display:flex;align-items:center;justify-content:center;height:3.5rem;border-radius:var(--border-radius-lg);background-color:var(--color-gold);color:var(--color-black);font-size:1.125rem;font-weight:700;letter-spacing:.025em;transition:opacity .15s ease, box-shadow .15s ease; box-shadow:0 4px 10px rgba(212,175,55,.2)} .btn-place-order:hover{opacity:.9}
    .btn-primary{display:inline-flex;align-items:center;justify-content:center;border-radius:var(--border-radius-lg);background-color:var(--color-gold);color:var(--color-black);font-weight:700;border:1px solid var(--color-gold);transition:opacity .15s ease, box-shadow .15s ease, background-color .15s ease; box-shadow:0 4px 10px rgba(212,175,55,.2)} .btn-primary:hover{opacity:.9} .btn-primary:focus{outline:none; box-shadow:0 0 0 1px var(--color-border-focus)}
    .btn-secondary{display:inline-flex;align-items:center;justify-content:center;border-radius:var(--border-radius-lg);background-color:transparent;color:var(--color-text-light);font-weight:600;border:1px solid var(--color-border);transition:background-color .15s ease, box-shadow .15s ease, border-color .15s ease, color .15s ease} .btn-secondary:hover{background-color:var(--color-bg-input-hover); border-color:var(--color-gold)} .btn-secondary:focus{outline:none; box-shadow:0 0 0 1px var(--color-border-focus)}
    
    .form-input[readonly] {
        background-color: #f9f9f9;
        color: #555;
        cursor: not-allowed;
        border-color: #eee
    }

    .btn-edit-info {
        font-size: .85rem;
        color: #D4AF37;
        text-decoration: underline;
        cursor: pointer;
        background: none;
        border: none;
        margin-left: 10px;
        padding: 0
    }

    .btn-edit-info:hover {
        color: #b5952f
    }

    select.form-input {
        background-color: #0f0f0f;
        color: #fff;
        border: 1px solid #D4AF37;
        padding: 8px 12px;
        border-radius: 8px;
        appearance: none
    }

    .form-input::placeholder {
        color: #aaa
    }

    select.form-input option {
        color: #111;
        background: #fff
    }

    select.form-input optgroup {
        color: #111;
        background: #fff
    }

    select.form-input option[value=""] {
        color: #888
    }
    
    .form-card {
        background-color: var(--color-bg-input);
        border: 1px solid var(--color-border);
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
    }
    
    .address-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .col-text-light {
        color: var(--color-text-dim);
        font-weight: 500;
    }
</style>
<main class="checkout-main-container">
    <div class="checkout-page-header">
        <p class="checkout-page-title">Hoàn Tất Đơn Hàng</p>
        <p class="checkout-page-subtitle">Kiểm tra thông tin và đặt hàng</p>
    </div>
    <?php if (!empty($error_message)): ?>
            <div
                style="max-width:1200px;margin:0 auto 12px auto;color:#fff;background:#B00020;padding:10px 12px;border-radius:8px;">
                Lỗi: <?php echo htmlspecialchars($error_message); ?></div><?php endif; ?>
    <form class="checkout-layout-grid" action="main.php?r=checkout" method="POST" id="checkoutForm">
        <?php echo csrf_input(); ?>
        <input type="hidden" name="cart_json" id="cartJsonInput" />
        <input type="hidden" name="voucher_code" id="voucherCodeHidden" />
        <div class="checkout-form-column">
            <section>
                <h3 class="form-section-title">Địa Chỉ Giao Hàng</h3>
                <div id="addressBook" class="form-card">
                    <?php if (!empty($address_list)): ?>
                            <div class="address-list">
                                <?php foreach ($address_list as $idx => $addr): ?>
                                        <label class="address-item" style="display:flex; align-items:center; gap:12px; padding:8px 0;">
                                            <input type="radio" name="address_id" value="<?php echo (int) $addr['ma_dia_chi']; ?>" <?php echo $idx === 0 ? 'checked' : ''; ?> required />
                                            <div>
                                                <div class="col-text-light"><?php echo htmlspecialchars($addr['ho_ten_nguoi_nhan']); ?>
                                                    • <?php echo htmlspecialchars($addr['so_dien_thoai']); ?></div>
                                                <div><?php echo htmlspecialchars($addr['dia_chi_chi_tiet']); ?></div>
                                            </div>
                                        </label>
                                <?php endforeach; ?>
                            </div>
                    <?php else: ?>
                            <p>Chưa có địa chỉ. Vui lòng thêm trong <a href="main.php?r=addresses"
                                    style="color:#D4AF37; text-decoration:underline;">Sổ địa chỉ</a>.</p>
                    <?php endif; ?>
                    <div style="margin-top:12px; color: rgba(255,255,255,0.7);"><span>Nếu muốn thêm/sửa địa chỉ, vui
                            lòng vào <a href="main.php?r=addresses" style="color:#D4AF37; text-decoration:underline;">Sổ
                                địa chỉ</a>.</span></div>
                </div>
            </section>
            <section style="margin-top: 2rem;">
                <h3 class="form-section-title">Phương Thức Thanh Toán</h3>
                <div class="radio-group">
                    <label class="radio-option"><input name="payment_method" type="radio" class="form-radio" value="1"
                            checked />
                        <div class="radio-option-content">
                            <p>Thanh toán khi nhận hàng (COD)</p><span>Bạn sẽ thanh toán khi shipper giao hàng
                                đến.</span>
                        </div>
                    </label>
                    <label class="radio-option"><input name="payment_method" type="radio" class="form-radio"
                            value="2" />
                        <div class="radio-option-content">
                            <p>Chuyển khoản ngân hàng</p><span>Quét mã QR hoặc chuyển khoản trực tiếp.</span>
                        </div>
                    </label>
                </div>
                <div style="margin-top:1rem;"><label class="form-label">Ghi chú đơn hàng</label><textarea
                        class="form-input" name="note" rows="2" placeholder="Ví dụ: Giao giờ hành chính"></textarea>
                </div>
            </section>
        </div>
        <div class="checkout-summary-column">
            <div class="summary-box">
                <div class="summary-banner"
                    style="margin-bottom:12px; background:#222; color:#D4AF37; padding:10px; border-radius:12px;">
                    <span>Chào <?php echo htmlspecialchars($user_info['ho_ten'] ?? 'bạn'); ?>, bạn còn 1 voucher chưa
                        dùng. Áp dụng ở bước sau.</span>
                </div>
                <h3 class="summary-title">Đơn Hàng Của Bạn</h3>
                <div class="summary-items-list" id="summaryItems"></div>
                <div class="summary-divider"></div>
                <div class="summary-costs">
                    <div class="summary-cost-row"><span>Tạm tính</span><span id="subtotalText">0₫</span></div>
                    <div class="summary-cost-row"><span>Phí vận chuyển</span><span>Miễn phí</span></div>
                    <div class="voucher-row" id="voucherRow"><input class="form-input voucher-input" type="text"
                            id="voucherInput" placeholder="Nhập mã voucher" /><button type="button" class="btn-primary"
                            id="applyVoucherBtn" style="height:48px; padding:0 16px;">Áp dụng</button></div>
                    <div class="voucher-feedback" id="voucherFeedback"></div>
                    <div class="summary-cost-row" id="discountRow" style="display:none;"><span>Giảm giá</span><span
                            id="discountText">-0₫</span></div>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-total"><span>Tổng cộng</span><span class="total-price" id="grandTotalText">0₫</span>
                </div>
                <button class="btn-place-order" type="submit">ĐẶT HÀNG</button>
            </div>
        </div>
    </form>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const CART_KEY = 'saphira_cart';
        const ASSET_BASE = '/de/duan1/public/';
        function assetJs(path) { if (!path) return ASSET_BASE + 'img/placeholder.png'; if (/^https?:\/\//i.test(path)) return path; if (path.startsWith('/')) return path; if (path.startsWith('public/')) return ASSET_BASE + path.replace(/^public\//, ''); return ASSET_BASE + path; }
        const SERVER_CART = <?php echo json_encode($cart_items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch (e) { return []; } }
        function migrateFromServer() { const cart = getCart(); if (cart.length === 0 && Array.isArray(SERVER_CART) && SERVER_CART.length) { const mapped = SERVER_CART.map(s => ({ product_id: parseInt(s.product_id || 0, 10), variant_id: parseInt(s.ma_bien_the || 0, 10), name: s.ten_nuoc_hoa || '', price: parseInt(s.gia || 0, 10) || 0, image: s.hinh_anh || '', volume_ml: parseInt(s.dung_tich_ml || 0, 10) || 0, quantity: parseInt(s.so_luong || 1, 10) || 1, })); localStorage.setItem(CART_KEY, JSON.stringify(mapped)); return mapped; } return cart; }
        function renderSummary() { const list = document.getElementById('summaryItems'); const cart = migrateFromServer(); list.innerHTML = ''; let subtotal = 0; cart.forEach(it => { const line = (it.price || 0) * (it.quantity || 1); subtotal += line; const div = document.createElement('div'); div.className = 'summary-item'; div.innerHTML = `<img class="summary-item-image" src="${assetJs(it.image)}"/><div class="summary-item-info"><p>${it.name || ''}</p><span>${it.volume_ml || 0}ml x ${it.quantity || 1}</span></div><p class="summary-item-price">${new Intl.NumberFormat('vi-VN').format(line)}₫</p>`; list.appendChild(div); }); document.getElementById('subtotalText').textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + '₫'; const discount = calcDiscount(subtotal); const dRow = document.getElementById('discountRow'); const dText = document.getElementById('discountText'); if (discount > 0) { dRow.style.display = ''; dText.textContent = '-' + new Intl.NumberFormat('vi-VN').format(discount) + '₫'; } else { dRow.style.display = 'none'; } const grand = Math.max(0, subtotal - discount); document.getElementById('grandTotalText').textContent = new Intl.NumberFormat('vi-VN').format(grand) + '₫'; }
        renderSummary();
        const VOUCHER_KEY = 'saphira_voucher';
        function calcDiscount(subtotal) { try { const v = localStorage.getItem(VOUCHER_KEY) || ''; const code = (v || '').trim().toUpperCase(); if (!code) return 0; if (code === 'SAPHIRA10') { return Math.floor(subtotal * 0.10); } if (code === 'GOLD50K') { return Math.min(50000, subtotal); } return 0; } catch (e) { return 0; } }
        (function () { const input = document.getElementById('voucherInput'); const btn = document.getElementById('applyVoucherBtn'); const fb = document.getElementById('voucherFeedback'); const hidden = document.getElementById('voucherCodeHidden'); const saved = localStorage.getItem(VOUCHER_KEY) || ''; if (saved) { input.value = saved; hidden.value = saved; } function apply() { const code = (input.value || '').trim().toUpperCase(); if (!code) { fb.textContent = 'Vui lòng nhập mã voucher'; fb.className = 'voucher-feedback error'; return; } const ok = ['SAPHIRA10', 'GOLD50K'].includes(code); if (!ok) { fb.textContent = 'Mã không hợp lệ'; fb.className = 'voucher-feedback error'; hidden.value = ''; localStorage.removeItem(VOUCHER_KEY); renderSummary(); return; } localStorage.setItem(VOUCHER_KEY, code); hidden.value = code; fb.textContent = 'Áp dụng thành công'; fb.className = 'voucher-feedback success'; renderSummary(); } btn.addEventListener('click', apply); input.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); apply(); } }); })();
        const form = document.getElementById('checkoutForm');
        form.addEventListener('submit', function (e) { const cart = getCart(); const missing = cart.find(it => !it.variant_id || it.variant_id <= 0); if (missing) { e.preventDefault(); alert('Vui lòng chọn dung tích cho tất cả sản phẩm trước khi đặt hàng.'); return; } document.getElementById('cartJsonInput').value = JSON.stringify(cart); });
    });
</script>