/* === Global Add To Cart Function === */
/* This file should be included on all pages that have add to cart buttons */

function addToCart(element) {
    const productId = parseInt(element.getAttribute('data-id') || '0', 10);
    const variantId = parseInt(element.getAttribute('data-variant') || '0', 10);
    const name = element.getAttribute('data-name') || '';
    const price = parseInt(element.getAttribute('data-price') || '0', 10);
    const image = element.getAttribute('data-image') || '';
    const volume_ml = parseInt(element.getAttribute('data-volume') || '0', 10);

    const CART_KEY = 'saphira_cart';

    function getCart() {
        try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; }
        catch (e) { return []; }
    }

    function saveCart(cart) {
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
    }

    function updateCartCount() {
        const cart = getCart();
        const count = cart.reduce((s, i) => s + (i.quantity || 1), 0);
        const el = document.querySelector('.cart-count');
        if (el) el.textContent = count;
    }

    // Get current variant info from page (for product details page)
    let currentVariantId = variantId;
    let currentPrice = price;
    let currentVolume = volume_ml;

    // Check if on product details page with variant selector
    const variantHidden = document.getElementById('variantIdHidden');
    const priceEl = document.getElementById('productPrice');
    const activeVariantBtn = document.querySelector('.variant-btn.active');

    if (variantHidden && variantHidden.value) {
        currentVariantId = parseInt(variantHidden.value, 10) || variantId;
    }
    if (priceEl) {
        const priceText = priceEl.textContent || '';
        currentPrice = parseInt(priceText.replace(/\D/g, ''), 10) || price;
    }
    if (activeVariantBtn) {
        const mlEl = activeVariantBtn.querySelector('.ml');
        if (mlEl) {
            currentVolume = parseInt(mlEl.textContent.replace(/\D/g, ''), 10) || 0;
        }
        // Also get variant id from active button
        const btnVariantId = activeVariantBtn.getAttribute('data-id');
        if (btnVariantId) {
            currentVariantId = parseInt(btnVariantId, 10) || currentVariantId;
        }
    }

    const cart = getCart();

    // Find existing item by variant_id if available, otherwise by product_id
    let found = null;
    if (currentVariantId > 0) {
        found = cart.find(item => item.variant_id === currentVariantId);
    } else {
        found = cart.find(item => item.product_id === productId && !item.variant_id);
    }

    if (found) {
        found.quantity = (found.quantity || 1) + 1;
    } else {
        cart.push({
            product_id: productId,
            variant_id: currentVariantId,
            name: name,
            price: currentPrice,
            image: image,
            volume_ml: currentVolume,
            quantity: 1
        });
    }

    saveCart(cart);
    updateCartCount();

    // Show toast notification
    if (currentVolume > 0) {
        showCartToast(name + ' - ' + currentVolume + 'ml');
    } else {
        showCartToast(name);
    }
}

// Toast Notification Function
function showCartToast(productName) {
    // Remove existing toast if any
    const existingToast = document.querySelector('.cart-toast');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'cart-toast';
    toast.innerHTML = `
        <div class="cart-toast-icon">
            <span class="material-symbols-outlined">check_circle</span>
        </div>
        <div class="cart-toast-content">
            <p class="cart-toast-title">Đã thêm vào giỏ hàng!</p>
            <p class="cart-toast-product">${productName}</p>
        </div>
        <button class="cart-toast-close" onclick="this.parentElement.remove()">
            <span class="material-symbols-outlined">close</span>
        </button>
    `;

    // Add styles if not already added
    if (!document.getElementById('cart-toast-styles')) {
        const style = document.createElement('style');
        style.id = 'cart-toast-styles';
        style.textContent = `
            .cart-toast {
                position: fixed;
                top: 100px;
                right: 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 16px 20px;
                background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
                border: 1px solid rgba(212, 175, 55, 0.3);
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
                z-index: 10000;
                animation: toastSlideIn 0.4s ease-out;
                max-width: 350px;
            }
            @keyframes toastSlideIn {
                from {
                    opacity: 0;
                    transform: translateX(100px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            .cart-toast-icon {
                color: #4ade80;
                font-size: 28px;
                display: flex;
            }
            .cart-toast-icon .material-symbols-outlined {
                font-size: 28px;
            }
            .cart-toast-content {
                flex: 1;
            }
            .cart-toast-title {
                color: #d4af37;
                font-weight: 600;
                font-size: 14px;
                margin: 0 0 4px 0;
            }
            .cart-toast-product {
                color: #fff;
                font-size: 13px;
                margin: 0;
                opacity: 0.9;
            }
            .cart-toast-close {
                background: none;
                border: none;
                color: #888;
                cursor: pointer;
                padding: 4px;
                display: flex;
                transition: color 0.2s;
            }
            .cart-toast-close:hover {
                color: #fff;
            }
            .cart-toast-close .material-symbols-outlined {
                font-size: 18px;
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.animation = 'toastSlideIn 0.3s ease-out reverse';
            setTimeout(() => toast.remove(), 280);
        }
    }, 3000);
}

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', function () {
    const CART_KEY = 'saphira_cart';
    try {
        const cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];
        const count = cart.reduce((s, i) => s + (i.quantity || 1), 0);
        const el = document.querySelector('.cart-count');
        if (el) el.textContent = count;
    } catch (e) { }
});
