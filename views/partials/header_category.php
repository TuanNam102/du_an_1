<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/helpers.php';
$active_page = $active_page ?? '';
$page_title = $page_title ?? 'SAPHIRA';
$user_name = $_SESSION['user_name'] ?? '';
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($page_title); ?> - SAPHIRA</title>
    <base href="<?php echo base_url(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo asset('css/style-men.css'); ?>" />
    <link rel="stylesheet" href="<?php echo asset('css/account-menu.css'); ?>" />
</head>

<body>
    <header class="page-header">
        <div class="header-logo">
            <a href="main.php?r=home" style="display:flex;align-items:center">
                <div style="height:135px;width:160px;overflow:hidden;display:flex;align-items:center">
                    <img src="<?php echo asset('img/logo/logo.png'); ?>" alt="SAPHIRA"
                        style="height:135px;display:block" />
                </div>
            </a>
        </div>
        <nav class="header-nav">
            <a href="main.php?r=home" class="<?php echo ($active_page == 'index') ? 'active' : ''; ?>">Trang Chủ</a>
            <a href="main.php?r=category/men" class="<?php echo ($active_page == 'men') ? 'active' : ''; ?>">Nước Hoa
                Nam</a>
            <a href="main.php?r=category/women" class="<?php echo ($active_page == 'women') ? 'active' : ''; ?>">Nước
                Hoa Nữ</a>
            <a href="main.php?r=category/unisex" class="<?php echo ($active_page == 'unisex') ? 'active' : ''; ?>">Nước
                Hoa Unisex</a>
        </nav>
        <div class="header-actions">
            <a href="#" title="Tìm kiếm"><span class="material-symbols-outlined">search</span></a>
            <a href="main.php?r=cart" title="Giỏ hàng"><span class="material-symbols-outlined">shopping_bag</span><span
                    class="cart-count">0</span></a>
            <?php if ($is_logged_in): ?>
                <div class="account-menu">
                    <a href="main.php?r=account" title="Tài khoản của tôi"
                        style="display: flex; align-items: center; color: inherit; text-decoration: none;">
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
                <a href="main.php?r=login" title="Đăng nhập"><span class="material-symbols-outlined">person</span></a>
            <?php endif; ?>
        </div>
    </header>