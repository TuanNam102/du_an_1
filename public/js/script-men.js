/* script-men.js - Page-specific functionality for category pages */
/* Note: addToCart is defined in cart.js - do not duplicate here */

function initMen() {

    // Xử lý Filter Tags
    const filterTagsContainer = document.getElementById("filter-tags");

    if (filterTagsContainer) {
        const filterButtons = filterTagsContainer.querySelectorAll(".filter-tag-button");

        filterButtons.forEach(button => {
            button.addEventListener("click", function () {
                // 1. Bỏ 'active' khỏi tất cả các nút
                filterButtons.forEach(btn => btn.classList.remove("active"));

                // 2. Thêm 'active' cho nút vừa bấm
                this.classList.add("active");

                // (Trong một ứng dụng thật, bạn sẽ gọi hàm lọc sản phẩm ở đây)
                // Ví dụ: filterProducts(this.innerText);
            });
        });
    }

    const lazyNodes = document.querySelectorAll('.card-image[data-bg]');
    if (lazyNodes.length) {
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const el = e.target;
                    const bg = el.getAttribute('data-bg');
                    if (bg) { el.style.backgroundImage = 'url("' + bg + '")'; el.removeAttribute('data-bg'); }
                    io.unobserve(el);
                }
            });
        }, { rootMargin: '100px' });
        lazyNodes.forEach(n => io.observe(n));
    }

    // === LocalStorage Cart ===
    const CART_KEY = 'saphira_cart';
    const ASSET_BASE = '/de/duan1/public/';
    function assetJs(path) {
        if (!path) return ASSET_BASE + 'img/placeholder.png';
        if (/^https?:\/\//i.test(path)) return path;
        const p = path.startsWith('/') ? path.slice(1) : path;
        return ASSET_BASE + p;
    }
    function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch (e) { return []; } }
    function saveCart(cart) { localStorage.setItem(CART_KEY, JSON.stringify(cart)); }
    function cartCount() { return getCart().reduce((s, i) => s + (i.quantity || 1), 0); }
    function updateCartCount() { const el = document.querySelector('.cart-count'); if (el) el.textContent = cartCount(); }

    // Intercept product add-to-cart forms
    const addForms = document.querySelectorAll('form[action*="cart_add.php"]');
    addForms.forEach(f => {
        f.addEventListener('submit', function (e) {
            e.preventDefault();
            const pid = parseInt(f.querySelector('input[name="product_id"]')?.value || '0', 10);
            const vid = parseInt(f.querySelector('input[name="variant_id"]')?.value || '0', 10);
            const card = f.closest('.product-card');
            const name = card?.querySelector('.product-name')?.textContent?.trim() || document.querySelector('.section-title')?.textContent?.trim() || '';
            const priceText = card?.querySelector('.product-price')?.textContent || '0';
            const price = parseInt((priceText || '').replace(/\D/g, ''), 10) || 0;
            let image = '';
            const imgEl = card?.querySelector('img') || document.getElementById('main-product-image');
            if (imgEl) { image = imgEl.getAttribute('src') || ''; }
            const volEl = f.querySelector('.variant-btn.active .ml') || card?.querySelector('.ml');
            const volume_ml = volEl ? parseInt(volEl.textContent.replace(/\D/g, ''), 10) || 0 : 0;
            const cart = getCart();
            const found = cart.find(it => (vid ? it.variant_id === vid : it.product_id === pid));
            if (found) { found.quantity = (found.quantity || 1) + 1; }
            else { cart.push({ product_id: pid, variant_id: vid, name, price, image, volume_ml, quantity: 1 }); }
            saveCart(cart); updateCartCount();
            if (typeof showCartToast === 'function') showCartToast(name);
        });
    });

    // Also capture explicit buttons with class .btn-add-to-cart
    const addButtons = document.querySelectorAll('.btn-add-to-cart');
    addButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const f = this.closest('form');
            if (!f) return;
            const pid = parseInt(f.querySelector('input[name="product_id"]')?.value || '0', 10);
            const vid = parseInt(f.querySelector('input[name="variant_id"]')?.value || '0', 10);
            const card = f.closest('.product-card');
            const name = card?.querySelector('.card-title')?.textContent?.trim() || '';
            const priceText = card?.querySelector('.card-price')?.textContent || '0';
            const price = parseInt((priceText || '').replace(/\D/g, ''), 10) || 0;
            const bgEl = card?.querySelector('.card-image');
            let image = '';
            if (bgEl) {
                const bg = bgEl.getAttribute('data-bg');
                image = bg ? bg : (bgEl.style.backgroundImage || '').replace(/^url\(["']?(.+?)["']?\)$/, '$1');
            }
            const cart = getCart();
            const found = cart.find(it => (vid ? it.variant_id === vid : it.product_id === pid));
            if (found) { found.quantity = (found.quantity || 1) + 1; }
            else { cart.push({ product_id: pid, variant_id: vid, name, price, image, volume_ml: 0, quantity: 1 }); }
            saveCart(cart); updateCartCount();
            if (typeof showCartToast === 'function') showCartToast(name);
        });
    });

    updateCartCount();
}
(function () {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMen);
    else initMen();
})();
