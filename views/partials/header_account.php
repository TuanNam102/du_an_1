<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/helpers.php';
$page_title = $page_title ?? 'Tài Khoản';
$user_name = $_SESSION['user_name'] ?? '';
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAPHIRA - <?php echo htmlspecialchars($page_title); ?></title>
    <base href="<?php echo base_url(); ?>">
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo asset('css/account-menu.css'); ?>" />
    <style>
        :root {
            --font-display: 'Plus Jakarta Sans', sans-serif;
            --font-serif: 'Cormorant Garamond', serif;
            --color-primary: #D4AF37;
            --color-bg-start: #222222;
            --color-bg-end: #000000;
            --color-bg-content: #111111;
            --color-header: rgba(17, 17, 17, 0.8);
            --color-text-light: #ffffff;
            --color-text-dim: rgba(255, 255, 255, 0.8);
            --color-text-dimmer: rgba(255, 255, 255, 0.6);
            --color-text-dimmest: rgba(255, 255, 255, 0.5);
            --color-border: rgba(255, 255, 255, 0.1);
            --color-hover: rgba(255, 255, 255, 0.05);
            --color-primary-tint: rgba(212, 175, 55, 0.2);
            --color-green: #22c55e;
            --color-blue: #3b82f6;
            --color-yellow: #eab308;
            --border-radius: 0.5rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.5rem;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-display);
            background-image: linear-gradient(to bottom, var(--color-bg-start), var(--color-bg-end));
            color: var(--color-text-light);
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-serif);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
            border: none;
            background: none;
            cursor: pointer;
        }

        /* === PREMIUM HEADER DESIGN === */
        .account-header-sticky {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(212, 175, 55, 0.15);
            padding: 0 1.5rem;
            height: 70px;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.95) 0%, rgba(17, 17, 17, 0.9) 100%);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        @media (min-width: 768px) {
            .account-header-sticky {
                padding: 0 3rem;
                height: 75px;
            }
        }

        @media (min-width: 1024px) {
            .account-header-sticky {
                padding: 0 4rem;
                height: 80px;
            }
        }

        .account-header-container {
            display: flex;
            width: 100%;
            max-width: 1400px;
            align-items: center;
            justify-content: space-between;
        }

        .header-logo-container {
            display: flex;
            align-items: center;
        }

        .header-logo-container a {
            display: flex;
            align-items: center;
            transition: opacity 0.2s ease;
        }

        .header-logo-container a:hover {
            opacity: 0.85;
        }

        .account-nav-desktop {
            display: none;
        }

        @media (min-width: 1024px) {
            .account-nav-desktop {
                display: flex;
                flex: 1;
                align-items: center;
                justify-content: center;
                gap: 2.5rem;
            }
        }

        /* Account icons container - positioned at the right */
        .account-nav-icons-wrapper {
            display: none;
        }

        @media (min-width: 1024px) {
            .account-nav-icons-wrapper {
                display: flex;
                align-items: center;
            }
        }

        .account-nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .account-nav-links a {
            position: relative;
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.8125rem;
            font-weight: 500;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            padding: 0.5rem 0;
            transition: all 0.25s ease;
        }

        .account-nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1.5px;
            background: linear-gradient(90deg, var(--color-primary), #f4d03f);
            transition: width 0.3s ease;
        }

        .account-nav-links a:hover {
            color: var(--color-primary);
        }

        .account-nav-links a:hover::after {
            width: 100%;
        }

        .account-nav-icons {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 1.5rem;
            padding-left: 1.5rem;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }

        .icon-button {
            position: relative;
            display: flex;
            height: 2.5rem;
            width: 2.5rem;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: transparent;
            color: rgba(255, 255, 255, 0.85);
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .icon-button:hover {
            color: var(--color-primary);
            background-color: rgba(212, 175, 55, 0.08);
            border-color: rgba(212, 175, 55, 0.2);
        }

        .icon-button .material-symbols-outlined {
            font-size: 1.35rem;
        }

        /* Cart count badge */
        .icon-button .cart-count {
            position: absolute;
            top: 2px;
            right: 2px;
            min-width: 16px;
            height: 16px;
            padding: 0 4px;
            background: linear-gradient(135deg, var(--color-primary), #f4d03f);
            color: #000;
            font-size: 0.65rem;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-button-mobile {
            display: flex;
            height: 2.5rem;
            width: 2.5rem;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            background-color: transparent;
            color: var(--color-text-light);
            transition: background-color .15s ease;
        }

        .icon-button-mobile:hover {
            background-color: var(--color-hover);
        }

        .icon-button-mobile .material-symbols-outlined {
            font-size: 1.875rem;
        }

        @media (min-width:1024px) {
            .icon-button-mobile {
                display: none;
            }
        }

        .account-main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 2.5rem 1rem;
            gap: 2rem;
        }

        @media (min-width:640px) {
            .account-main-container {
                padding: 4rem 2.5rem;
            }
        }

        @media (min-width:1024px) {
            .account-main-container {
                padding: 4rem 5rem;
            }
        }

        .account-page-header {
            display: flex;
            flex-direction: column;
            gap: .75rem;
            padding: 1rem;
            text-align: center;
            align-items: center;
        }

        .account-page-title {
            font-family: var(--font-serif);
            color: var(--color-primary);
            font-size: 3rem;
            font-weight: 700;
        }

        @media (min-width:768px) {
            .account-page-title {
                font-size: 3.75rem;
            }
        }

        .account-page-subtitle {
            color: var(--color-text-light);
            font-size: 1rem;
        }

        .account-layout-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width:1024px) {
            .account-layout-grid {
                grid-template-columns: repeat(12, 1fr);
                gap: 2.5rem;
            }
        }

        .account-sidebar {
            grid-column: span 1;
        }

        @media (min-width:1024px) {
            .account-sidebar {
                grid-column: span 4;
            }
        }

        @media (min-width:1280px) {
            .account-sidebar {
                grid-column: span 3;
            }
        }

        .sidebar-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            border-radius: var(--border-radius-lg);
            background-color: var(--color-bg-content);
            padding: 1.25rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .2);
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-avatar {
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            border-radius: 9999px;
            width: 3rem;
            height: 3rem;
            flex-shrink: 0;
        }

        .sidebar-user-text h2 {
            font-family: var(--font-display);
            font-size: 1rem;
            font-weight: 500;
            color: var(--color-text-light);
        }

        .sidebar-user-text p {
            color: var(--color-text-dimmer);
            font-size: .875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-nav {
            margin-top: 1rem;
            display: flex;
            flex-direction: column;
            gap: .25rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem 1rem;
            border-radius: var(--border-radius);
            transition: background-color .15s ease, color .15s ease;
        }

        .sidebar-link:hover {
            background-color: var(--color-hover);
        }

        .sidebar-link:hover .material-symbols-outlined,
        .sidebar-link:hover p {
            color: var(--color-primary);
        }

        .sidebar-link .material-symbols-outlined {
            font-size: 1.5rem;
            color: var(--color-text-dim);
            transition: color .15s ease;
        }

        .sidebar-link p {
            font-size: .875rem;
            font-weight: 500;
            color: var(--color-text-light);
            transition: color .15s ease;
        }

        .sidebar-link.active {
            background-color: var(--color-primary-tint);
            border-left: 4px solid var(--color-primary);
            padding-left: calc(1rem - 4px);
        }

        .sidebar-link.active .material-symbols-outlined,
        .sidebar-link.active p {
            color: var(--color-primary);
        }

        .account-content {
            grid-column: span 1;
            min-height: 500px;
        }

        @media (min-width:1024px) {
            .account-content {
                grid-column: span 8;
            }
        }

        @media (min-width:1280px) {
            .account-content {
                grid-column: span 9;
            }
        }

        .content-panel {
            display: flex;
            flex-direction: column;
            border-radius: var(--border-radius-lg);
            background-color: var(--color-bg-content);
            padding: 1.5rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .2);
        }

        @media (min-width:640px) {
            .content-panel {
                padding: 2rem;
            }
        }

        .content-title {
            font-family: var(--font-serif);
            color: var(--color-primary);
            font-size: 1.875rem;
            font-weight: 700;
            line-height: 1.2;
        }

        @media (min-width:640px) {
            .content-title {
                font-size: 2.25rem;
            }
        }

        .content-description {
            color: var(--color-text-dim);
            font-size: 1rem;
            line-height: 1.625;
            margin-top: 1rem;
        }

        .recent-orders-section {
            margin-top: 2rem;
            border-top: 1px solid var(--color-border);
            padding-top: 2rem;
        }

        .recent-orders-title {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-text-light);
            margin-bottom: 1rem;
        }

        .table-container {
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            text-align: left;
            min-width: 500px;
        }

        .orders-table thead tr {
            border-bottom: 1px solid var(--color-border);
        }

        .orders-table th {
            color: var(--color-text-dimmer);
            font-size: .75rem;
            text-transform: uppercase;
            padding: .75rem 1rem;
            font-weight: 400;
        }

        .orders-table tbody tr {
            border-bottom: 1px solid var(--color-border);
            font-size: .875rem;
            transition: background-color .15s ease;
        }

        .orders-table tbody tr:last-child {
            border-bottom: none;
        }

        .orders-table tbody tr:hover {
            background-color: var(--color-hover);
        }

        .orders-table td {
            padding: 1rem;
            font-weight: 500;
        }

        .col-hidden-sm {
            display: none;
        }

        @media (min-width:640px) {
            .col-hidden-sm {
                display: table-cell;
            }
        }

        .col-text-right {
            text-align: right;
        }

        .status-badge {
            font-size: .75rem;
            font-weight: 500;
            padding: .25rem .5rem;
            border-radius: 9999px;
        }

        .status-delivered {
            background-color: rgba(34, 197, 94, .2);
            color: #22c55e;
        }

        .status-shipped {
            background-color: rgba(59, 130, 246, .2);
            color: #3b82f6;
        }

        .status-processing {
            background-color: rgba(234, 179, 8, .2);
            color: #eab308;
        }

        .view-all-orders {
            margin-top: 1.5rem;
            display: flex;
            justify-content: flex-end;
        }

        .btn-link {
            color: var(--color-primary);
            font-size: .875rem;
            font-weight: 700;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .account-footer {
            width: 100%;
            background-color: var(--color-bg-content);
            border-top: 1px solid var(--color-border);
            margin-top: auto;
        }

        .account-footer-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 4rem 1rem;
        }

        @media (min-width:640px) {
            .account-footer-container {
                padding: 4rem 2.5rem;
            }
        }

        @media (min-width:1024px) {
            .account-footer-container {
                padding: 4rem 5rem;
            }
        }

        .account-footer-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 3rem;
        }

        @media (min-width:768px) {
            .account-footer-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .footer-title {
            font-family: var(--font-serif);
            color: var(--color-primary);
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-description {
            color: var(--color-text-dimmer);
            font-size: .875rem;
            line-height: 1.625;
        }

        .footer-links,
        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: .5rem;
            list-style: none;
            font-size: .875rem;
            color: var(--color-text-dimmer);
        }

        .footer-links a {
            color: var(--color-text-dimmer);
            transition: color .15s ease;
        }

        .footer-links a:hover {
            color: var(--color-primary);
        }

        .footer-copyright {
            margin-top: 3rem;
            border-top: 1px solid var(--color-border);
            padding-top: 2rem;
            text-align: center;
        }

        .footer-copyright p {
            color: var(--color-text-dimmest);
            font-size: .75rem;
        }

        .account-menu {
            position: relative;
            display: inline-block;
        }

        .account-menu-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #111;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.5);
            z-index: 100;
            border-radius: 4px;
            padding: 5px 0;
            border: 1px solid #333;
        }

        .account-menu:hover .account-menu-content {
            display: block;
        }

        .account-menu-content a {
            color: #fff;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            text-align: left;
        }

        .account-menu-content a:hover {
            background-color: #222;
            color: #D4AF37;
        }

        .user-greeting {
            font-size: 14px;
            margin-right: 5px;
            font-weight: 600;
            display: none;
            color: #fff;
        }

        @media (min-width:768px) {
            .user-greeting {
                display: inline-block;
            }
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width:640px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .col-span-2 {
            grid-column: span 1;
        }

        @media (min-width:640px) {
            .col-span-2 {
                grid-column: span 2;
            }
        }

        .form-label {
            color: var(--color-text-dim);
            font-size: .875rem;
            font-weight: 500;
            padding-bottom: .5rem;
            display: block;
        }

        .form-input {
            display: flex;
            width: 100%;
            min-width: 0;
            flex: 1;
            resize: none;
            overflow: hidden;
            border-radius: .75rem;
            color: var(--color-text-light);
            background-color: rgba(255, 255, 255, .05);
            border: 1px solid var(--color-border);
            height: 3rem;
            padding: .75rem;
            font-size: 1rem;
            transition: all .15s ease;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, .5);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 1px rgba(212, 175, 55, .5);
        }

        select.form-input {
            appearance: none;
            background-color: #0f0f0f;
            color: #fff;
            border: 1px solid var(--color-primary);
        }

        select.form-input option,
        select.form-input optgroup {
            color: #111;
            background: #fff;
        }

        select.form-input option[value=""] {
            color: #888;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .75rem;
            background-color: var(--color-primary);
            color: #000;
            font-weight: 700;
            border: 1px solid var(--color-primary);
            transition: opacity .15s ease, box-shadow .15s ease, background-color .15s ease;
            box-shadow: 0 4px 10px rgba(212, 175, 55, .2);
        }

        .btn-primary:hover {
            opacity: .9;
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 1px rgba(212, 175, 55, .5);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .75rem;
            background-color: transparent;
            color: var(--color-text-light);
            font-weight: 600;
            border: 1px solid var(--color-border);
            transition: background-color .15s ease, box-shadow .15s ease, border-color .15s ease, color .15s ease;
        }

        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, .1);
            border-color: var(--color-primary);
        }

        .btn-secondary:focus {
            outline: none;
            box-shadow: 0 0 0 1px rgba(212, 175, 55, .5);
        }
    </style>
</head>

<body>
    <header class="account-header-sticky">
        <div class="account-header-container">
            <div class="header-logo-container" style="gap:0">
                <a href="main.php?r=home" style="display:flex;align-items:center">
                    <div style="height:135px;width:160px;overflow:hidden;display:flex;align-items:center"><img
                            src="<?php echo asset('img/logo/logo.png'); ?>" alt="SAPHIRA"
                            style="height:135px;display:block" /></div>
                </a>
            </div>
            <div class="account-nav-desktop">
                <nav class="account-nav-links">
                    <a href="main.php?r=home">Trang Chủ</a>
                    <a href="main.php?r=category/men">Nước Hoa Nam</a>
                    <a href="main.php?r=category/women">Nước Hoa Nữ</a>
                    <a href="main.php?r=category/unisex">Nước Hoa Unisex</a>
                </nav>
            </div>
            <div class="account-nav-icons-wrapper">
                <div class="account-nav-icons">
                    <a href="main.php?r=cart" class="icon-button" title="Giỏ hàng">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        <span class="cart-count">0</span>
                    </a>
                    <?php if ($is_logged_in): ?>
                        <div class="account-menu">
                            <a href="main.php?r=account" title="Tài khoản"
                                style="display:flex;align-items:center;gap:4px;color:#fff;">
                                <span class="user-greeting">Hi,
                                    <?php echo htmlspecialchars(strtok($user_name, ' ')); ?></span>
                                <span class="material-symbols-outlined">person</span>
                            </a>
                            <div class="account-menu-content">
                                <a href="main.php?r=account">Quản lý tài khoản</a>
                                <a href="main.php?r=orders">Đơn hàng của tôi</a>
                                <a href="main.php?r=logout" onclick="return confirm('Bạn muốn đăng xuất?')">Đăng xuất</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="main.php?r=login" class="icon-button" title="Đăng nhập">
                            <span class="material-symbols-outlined">person</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <button class="icon-button-mobile" title="Menu"><span class="material-symbols-outlined">menu</span></button>
        </div>
    </header>