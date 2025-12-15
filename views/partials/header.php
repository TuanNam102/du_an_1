<?php
include_once __DIR__ . '/../../config/helpers.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_name = $_SESSION['user_name'] ?? '';
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - Nước Hoa Cao Cấp</title>
    <base href="<?php echo base_url(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400..900&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>" />
    <link rel="stylesheet" href="<?php echo asset('css/hero-banner.css'); ?>" />
    <link rel="stylesheet" href="<?php echo asset('css/grid-layout.css'); ?>" />
    <link rel="stylesheet" href="<?php echo asset('css/category-cards.css'); ?>" />
    <link rel="stylesheet" href="<?php echo asset('css/account-menu.css'); ?>" />
</head>

<body>
    <div class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
    <script>(function () { var el = document.querySelector('.loading-overlay'); if (el) { el.classList.add('hidden'); } })();</script>
    <div class="search-modal">
        <div class="search-modal-content"><button class="search-modal-close"><span
                    class="material-symbols-outlined">close</span></button><input type="text" class="search-modal-input"
                placeholder="Tìm kiếm sản phẩm..."></div>
    </div>
    <div class="scroll-to-top"><span class="material-symbols-outlined">arrow_upward</span></div>
    <header class="main-header">
        <a href="main.php?r=home" class="logo-container">
            <img src="<?php echo asset('img/logo/logo.png'); ?>" alt="SAPHIRA" class="logo-img" />
        </a>
        <button class="mobile-menu-toggle"><span class="material-symbols-outlined">menu</span></button>
        <nav class="main-nav">
            <a href="main.php?r=home">Trang Chủ</a>
            <a href="main.php?r=category/men">Nước Hoa Nam</a>
            <a href="main.php?r=category/women">Nước Hoa Nữ</a>
            <a href="main.php?r=category/unisex">Nước Hoa Unisex</a>
            <a href="main.php?r=about">Về Chúng Tôi</a>
        </nav>
        <div class="header-actions">
            <a href="#" class="icon-button search-toggle" title="Tìm kiếm"><span
                    class="material-symbols-outlined">search</span></a>
            <a href="main.php?r=cart" class="icon-button" title="Giỏ hàng"><span
                    class="material-symbols-outlined">shopping_bag</span><span class="cart-count">0</span></a>
            <?php if ($is_logged_in): ?>
                <div class="account-menu">
                    <a href="main.php?r=account" class="icon-button" title="Tài khoản của tôi">
                        <span class="user-greeting">Hi, <?php echo htmlspecialchars(strtok($user_name, ' ')); ?></span>
                        <span class="material-symbols-outlined">person</span>
                    </a>
                    <div class="account-menu-content">
                        <a href="main.php?r=account">Quản lý tài khoản</a>
                        <a href="main.php?r=orders">Đơn hàng của tôi</a>
                        <a href="main.php?r=logout" onclick="return confirm('Bạn muốn đăng xuất?')">Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="main.php?r=login" class="icon-button" title="Đăng nhập"><span
                        class="material-symbols-outlined">person</span></a>
            <?php endif; ?>
        </div>
    </header>