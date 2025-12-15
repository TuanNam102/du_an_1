/* script.js - Page-specific functionality for homepage */
/* Note: addToCart is defined in cart.js - do not duplicate here */

/* === Hero Slider === */
function initMain() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) { overlay.classList.add('hidden'); }
    const slides = document.querySelectorAll(".hero-slide");
    const dots = document.querySelectorAll(".slider-dot");
    const prevButton = document.getElementById("prevSlide") || document.getElementById("heroPrev");
    const nextButton = document.getElementById("nextSlide") || document.getElementById("heroNext");

    let currentSlide = 0;
    let slideInterval;

    function showSlide(n) {
        if (!slides.length) return;
        slides[currentSlide]?.classList.remove("active");
        if (dots.length) dots[currentSlide]?.classList.remove("active");
        if (n >= slides.length) currentSlide = 0;
        else if (n < 0) currentSlide = slides.length - 1;
        else currentSlide = n;
        slides[currentSlide]?.classList.add("active");
        if (dots.length) dots[currentSlide]?.classList.add("active");
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    // Tự động chuyển slide
    function startSlideShow() {
        slideInterval = setInterval(nextSlide, 5000); // 5 giây
    }

    // Dừng tự động khi tương tác
    function stopSlideShow() {
        clearInterval(slideInterval);
    }

    // Gán sự kiện cho nút
    if (nextButton) {
        nextButton.addEventListener("click", () => {
            nextSlide();
            stopSlideShow();
        });
    }

    if (prevButton) {
        prevButton.addEventListener("click", () => {
            prevSlide();
            stopSlideShow();
        });
    }

    // Khởi chạy slider
    if (slides.length) startSlideShow();
    if (dots.length) {
        dots.forEach((dot, i) => {
            dot.addEventListener('click', function () {
                showSlide(i);
                stopSlideShow();
            });
        });
    }
    const lazyNodes = document.querySelectorAll('.product-image[data-bg], .product-image-alt[data-bg], .news-image[data-bg]');
    if (lazyNodes.length) {
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const el = e.target;
                    const bg = el.getAttribute('data-bg');
                    if (bg) { el.style.backgroundImage = 'url(' + bg + ')'; el.removeAttribute('data-bg'); }
                    io.unobserve(el);
                }
            });
        }, { rootMargin: '120px' });
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
    function getCart() {
        try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch (e) { return []; }
    }
    function saveCart(cart) { localStorage.setItem(CART_KEY, JSON.stringify(cart)); }
    function cartCount() { return getCart().reduce((s, i) => s + (i.quantity || 1), 0); }
    function updateCartCount() { const el = document.querySelector('.cart-count'); if (el) el.textContent = cartCount(); }
    function addItem(item) {
        const cart = getCart();
        const key = (it) => (item.variant_id && it.variant_id === item.variant_id) || (!item.variant_id && it.product_id === item.product_id);
        const found = cart.find(key);
        if (found) { found.quantity = (found.quantity || 1) + (item.quantity || 1); }
        else { cart.push({ product_id: item.product_id, variant_id: item.variant_id || 0, name: item.name || '', price: item.price || 0, image: item.image || '', volume_ml: item.volume_ml || 0, quantity: item.quantity || 1 }); }
        saveCart(cart); updateCartCount();
    }
    // Hook product detail add-to-cart
    const addForm = document.getElementById('addToCartForm');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const pid = parseInt(addForm.querySelector('input[name="product_id"]').value, 10);
            const vid = parseInt(document.getElementById('variantIdHidden').value, 10) || 0;
            const name = (document.querySelector('.section-title') || document.querySelector('.product-title') || document.querySelector('h1'))?.textContent?.trim() || '';
            const priceText = document.getElementById('productPrice')?.textContent || '0';
            const price = parseInt(priceText.replace(/\D/g, '') || '0', 10) || 0;
            const image = document.getElementById('main-product-image')?.getAttribute('src') || '';
            const volEl = document.querySelector('.variant-btn.active .ml');
            const volume_ml = volEl ? parseInt(volEl.textContent.replace(/\D/g, ''), 10) || 0 : 0;
            addItem({ product_id: pid, variant_id: vid, name, price, image, volume_ml, quantity: 1 });
            if (typeof showCartToast === 'function') showCartToast(name);
        });
    }
    const addForms = document.querySelectorAll('form[action*="cart_add.php"]');
    addForms.forEach(f => {
        f.addEventListener('submit', function (e) {
            e.preventDefault();
            const pid = parseInt(f.querySelector('input[name="product_id"]').value || '0', 10);
            const vidInput = f.querySelector('input[name="variant_id"]');
            const vid = vidInput ? parseInt(vidInput.value || '0', 10) : 0;
            const card = f.closest('.product-card, .product-card-alt');
            const nameEl = card?.querySelector('.product-name, .card-title');
            const name = nameEl ? nameEl.textContent.trim() : '';
            const priceEl = card?.querySelector('.product-price, .product-price-alt, .card-price');
            const priceText = priceEl ? priceEl.textContent : '0';
            const price = parseInt((priceText || '').replace(/\D/g, ''), 10) || 0;
            let image = '';
            const imgEl = card?.querySelector('img');
            if (imgEl) { image = imgEl.getAttribute('src') || ''; }
            const bgEl = card?.querySelector('.product-image, .product-image-alt, .card-image');
            if (!image && bgEl) {
                const bg = bgEl.getAttribute('data-bg');
                image = bg ? bg : (bgEl.style.backgroundImage || '').replace(/^url\(["']?(.+?)["']?\)$/, '$1');
            }
            addItem({ product_id: pid, variant_id: vid, name, price, image, volume_ml: 0, quantity: 1 });
            if (typeof showCartToast === 'function') showCartToast(name);
        });
    });
    const addButtons = document.querySelectorAll('.product-card .product-action-btn:nth-child(2)');
    addButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const card = this.closest('.product-card');
            const name = card?.querySelector('.product-name')?.textContent?.trim() || '';
            const idInput = card?.querySelector('input[name="product_id"]');
            const productId = idInput ? parseInt(idInput.value, 10) : 0;
            const priceText = card?.querySelector('.product-price')?.textContent || '0';
            const price = parseInt((priceText || '').replace(/\D/g, ''), 10) || 0;
            const bgEl = card?.querySelector('.card-image');
            let image = '';
            if (bgEl) {
                const bg = bgEl.getAttribute('data-bg');
                image = bg ? bg : (bgEl.style.backgroundImage || '').replace(/^url\(["']?(.+?)["']?\)$/, '$1');
            }
            addItem({ product_id: productId, variant_id: 0, name, price, image, volume_ml: 0, quantity: 1 });
            if (typeof showCartToast === 'function') showCartToast(name);
        });
    });
    updateCartCount();
}
(function () {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMain);
    else initMain();
})();
