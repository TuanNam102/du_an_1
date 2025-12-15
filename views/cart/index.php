<?php
session_start();
include_once 'config/helpers.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Giỏ Hàng</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Playfair+Display:wght@700;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo asset('css/style-cart.css'); ?>" />
</head>

<body>
    <?php include __DIR__ . '/../partials/header_category.php'; ?>
    <main class="cart-main-container">
        <div class="cart-page-header">
            <h1 class="cart-page-title">Giỏ Hàng Của Bạn</h1>
            <p class="cart-page-subtitle">Xem lại các sản phẩm bạn đã chọn</p>
        </div>
        <div class="cart-layout-grid">
            <div class="cart-items-column"></div>
            <div class="cart-summary-column">
                <div class="cart-summary-box">
                    <h2 class="summary-title">Tóm Tắt Đơn Hàng</h2>
                    <div class="summary-details">
                        <div class="summary-row"><span>Tạm tính</span><span>0 VND</span></div>
                        <div class="summary-row"><span>Phí vận chuyển</span><span>Miễn phí</span></div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total"><span class="total-label">Tổng cộng</span><span
                                class="total-price">0 VND</span></div>
                    </div>
                    <div class="discount-form"><label for="discount-code" class="sr-only">Mã giảm giá</label><input
                            class="discount-input" id="discount-code" placeholder="Mã giảm giá" type="text" /><button
                            class="btn-apply-discount">Áp dụng</button></div>
                    <a href="main.php?r=checkout" class="btn-checkout"
                        style="text-decoration: none; display: block; text-align: center;">Thanh Toán</a>
                </div>
            </div>
        </div>
        <div class="recommended-section">
            <h2 class="recommended-title">Có Thể Bạn Sẽ Thích</h2>
            <div class="recommended-grid"></div>
        </div>
    </main>
    <script>
        const CART_KEY = 'saphira_cart';
        const ASSET_BASE = '/de/duan1/public/';
        function assetJs(path) {
            if (!path) return ASSET_BASE + 'img/placeholder.png';
            if (/^https?:\/\//i.test(path)) return path;
            if (path.startsWith('/')) return path;
            if (path.startsWith('public/')) return ASSET_BASE + path.replace(/^public\//, '');
            return ASSET_BASE + path;
        }
        function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch (e) { return []; } }
        function saveCart(cart) { localStorage.setItem(CART_KEY, JSON.stringify(cart)); }
        function cartCount() { return getCart().reduce((s, i) => s + (i.quantity || 1), 0); }
        function updateCartCount() { var el = document.querySelector('.cart-count'); if (el) el.textContent = cartCount(); }
        function renderCart() {
            const itemsCol = document.querySelector('.cart-items-column');
            const summaryBox = document.querySelector('.cart-summary-box .summary-details');
            const checkoutBtn = document.querySelector('.btn-checkout');
            const cart = getCart();
            if (!itemsCol) return;
            const emptyHtml = '<div style="text-align: center; padding: 40px;"><p style="font-size: 1.1rem; margin-bottom: 20px;">Giỏ hàng của bạn đang trống.</p><a href="main.php?r=home" class="btn-outline-gold" style="text-decoration: none; display: inline-block; padding: 10px 20px;">Tiếp tục mua sắm</a></div>';
            if (cart.length === 0) {
                itemsCol.innerHTML = emptyHtml; if (checkoutBtn) { checkoutBtn.setAttribute('disabled', 'disabled'); checkoutBtn.style.opacity = '0.5'; checkoutBtn.style.cursor = 'not-allowed'; }
                if (summaryBox) { const rows = summaryBox.querySelectorAll('.summary-row span:last-child'); rows.forEach((r, i) => { r.textContent = i === 0 ? '0 VND' : 'Miễn phí'; }); const totalEl = document.querySelector('.total-price'); if (totalEl) totalEl.textContent = '0 VND'; }
                updateCartCount();
                return;
            }
            let html = '';
            cart.forEach((it, idx) => {
                const priceLine = (it.price || 0) * (it.quantity || 1);
                html += `<div class="cart-item">
                    <div class="cart-item-details">
                        <img class="cart-item-image" alt="${(it.name || 'Sản phẩm')}" src="${assetJs(it.image)}" loading="lazy" />
                        <div class="cart-item-info">
                            <p class="cart-item-name">${(it.name || 'Sản phẩm')}</p>
                            <p class="cart-item-meta">${it.volume_ml ? it.volume_ml + 'ml' : 'Chưa chọn dung tích'}</p>
                            <p class="cart-item-meta">${new Intl.NumberFormat('vi-VN').format(it.price || 0)} VND</p>
                        </div>
                    </div>
                    <div class="cart-item-actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn decrease" data-idx="${idx}" data-action="dec">-</button>
                            <span class="quantity-value">${it.quantity || 1}</span>
                            <button class="quantity-btn increase" data-idx="${idx}" data-action="inc">+</button>
                        </div>
                        <p class="cart-item-price">${new Intl.NumberFormat('vi-VN').format(priceLine)} VND</p>
                        <button class="cart-item-delete" data-idx="${idx}" title="Xóa">X</button>
                    </div>
                </div>`;
            });
            itemsCol.innerHTML = html;
            itemsCol.querySelectorAll('.quantity-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const idx = parseInt(this.getAttribute('data-idx'), 10);
                    const action = this.getAttribute('data-action');
                    const cart = getCart();
                    if (action === 'inc') { cart[idx].quantity = (cart[idx].quantity || 1) + 1; }
                    else { cart[idx].quantity = Math.max(1, (cart[idx].quantity || 1) - 1); }
                    saveCart(cart); updateCartCount(); renderCart();
                });
            });
            itemsCol.querySelectorAll('.cart-item-delete').forEach(btn => {
                btn.addEventListener('click', function () {
                    const idx = parseInt(this.getAttribute('data-idx'), 10);
                    const cart = getCart(); cart.splice(idx, 1); saveCart(cart); updateCartCount(); renderCart();
                });
            });
            const subtotal = cart.reduce((s, it) => s + ((it.price || 0) * (it.quantity || 1)), 0);
            if (summaryBox) {
                const rows = summaryBox.querySelectorAll('.summary-row span:last-child');
                if (rows[0]) rows[0].textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + ' VND';
                const totalEl = document.querySelector('.total-price');
                if (totalEl) totalEl.textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + ' VND';
            }
            if (checkoutBtn) { checkoutBtn.removeAttribute('disabled'); checkoutBtn.style.opacity = '1'; checkoutBtn.style.cursor = 'pointer'; }
            updateCartCount();
        }
        renderCart();
        document.addEventListener('DOMContentLoaded', renderCart);
        (function () {
            var checkoutBtn = document.querySelector('.btn-checkout');
            if (!checkoutBtn) return;
            checkoutBtn.addEventListener('click', function (e) {
                var cart = getCart();
                if (!cart || cart.length === 0) { e.preventDefault(); alert('Giỏ hàng đang trống'); return; }
            });
        })();
    </script>
    <?php include __DIR__ . '/../partials/footer_category.php'; ?>
</body>

</html>