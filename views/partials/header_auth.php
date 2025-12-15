<?php $page_title = $page_title ?? 'SAPHIRA'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - <?php echo htmlspecialchars($page_title); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="/de/duan1/public/css/style-login.css" />
</head>
<body>
    <header class="login-header-sticky">
        <div class="header-logo-container">
            <a href="main.php?r=home" style="display:flex;align-items:center"><div style="height:135px;width:160px;overflow:hidden;display:flex;align-items:center"><img src="/de/duan1/public/img/logo/logo.png" alt="SAPHIRA" style="height:135px;display:block" /></div></a>
        </div>
        <nav class="header-nav-login"><a href="main.php?r=home">Trang Chủ</a><a href="main.php?r=category/men">Nước Hoa Nam</a><a href="main.php?r=category/women">Nước Hoa Nữ</a><a href="main.php?r=category/unisex">Nước Hoa Unisex</a></nav>
        <div class="header-actions-login"><a href="main.php?r=cart" class="icon-button-login" title="Giỏ hàng"><span class="material-symbols-outlined">shopping_bag</span><span class="cart-count">0</span></a><a href="main.php?r=login" class="icon-button-login active" title="Tài khoản"><span class="material-symbols-outlined">person</span></a></div>
    </header>

