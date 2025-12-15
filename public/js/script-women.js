/* script-women.js - Page-specific functionality for women category */
/* Note: addToCart is defined in cart.js - do not duplicate here */

document.addEventListener("DOMContentLoaded", function () {

    // Xử lý Filter Pills
    const filterPillsContainer = document.getElementById("filter-pills");

    if (filterPillsContainer) {
        const filterButtons = filterPillsContainer.querySelectorAll(".filter-pill");

        filterButtons.forEach(button => {
            button.addEventListener("click", function () {
                // 1. Bỏ 'active' khỏi tất cả các nút
                filterButtons.forEach(btn => btn.classList.remove("active"));

                // 2. Thêm 'active' cho nút vừa bấm
                this.classList.add("active");

                // (Chức năng lọc sản phẩm thật sẽ được gọi ở đây)
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
});
