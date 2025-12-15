document.addEventListener("DOMContentLoaded", function() {
    
    const accountNav = document.getElementById("account-nav");
    
    if (accountNav) {
        const navLinks = accountNav.querySelectorAll(".sidebar-link");

        navLinks.forEach(link => {
            link.addEventListener("click", function(event) {
                // (Ngăn chuyển trang nếu href là #)
                if (this.href.endsWith("#")) {
                    event.preventDefault();
                }

                // Bỏ active khỏi tất cả các link
                navLinks.forEach(nav => nav.classList.remove("active"));
                
                // Thêm active cho link vừa bấm
                this.classList.add("active");
            });
        });
    }
});