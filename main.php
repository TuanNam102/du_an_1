<?php
require __DIR__ . '/core/Router.php';

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/Controllers/' . $class . '.php',
        __DIR__ . '/app/Models/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) {
            require $p;
            return;
        }
    }
});

$router = new Router();

$router->add('home', function () {
    (new HomeController())->index(); });
$router->add('', function () {
    (new HomeController())->index(); });
$router->add('about', function () {
    (new HomeController())->about(); });
$router->add('product/details', function () {
    (new ProductController())->details(); });
$router->add('cart', function () {
    (new CartController())->index(); });
$router->add('cart/update', function () {
    (new CartController())->update(); });
$router->add('cart/add', function () {
    (new CartController())->add(); });
$router->add('cart/delete', function () {
    (new CartController())->delete(); });
$router->add('checkout', function () {
    (new CheckoutController())->index(); });
$router->add('account', function () {
    (new AccountController())->index(); });
$router->add('login', function () {
    (new AccountController())->login(); });
$router->add('register', function () {
    (new AccountController())->register(); });
$router->add('orders', function () {
    (new AccountController())->orders(); });
$router->add('profile', function () {
    (new AccountController())->profile(); });
$router->add('addresses', function () {
    (new AccountController())->addresses(); });
$router->add('order/detail', function () {
    (new AccountController())->orderDetail(); });
$router->add('logout', function () {
    (new AccountController())->logout(); });
$router->add('category/men', function () {
    (new CategoryController())->men(); });
$router->add('category/women', function () {
    (new CategoryController())->women(); });
$router->add('category/unisex', function () {
    (new CategoryController())->unisex(); });

// Admin routes
$router->add('admin/dashboard', function () {
    (new AdminController())->dashboard(); });
$router->add('admin/products', function () {
    (new AdminProductController())->index(); });
$router->add('admin/orders', function () {
    (new AdminOrderController())->index(); });
$router->add('admin/customers', function () {
    (new AdminCustomerController())->index(); });
$router->add('admin/categories', function () {
    (new AdminCategoryController())->index(); });

$router->dispatch();
