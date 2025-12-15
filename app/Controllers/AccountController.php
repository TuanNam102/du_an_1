<?php
class AccountController {
    public function index(): void { require __DIR__ . '/../../views/account/index.php'; }
    public function login(): void { require __DIR__ . '/../../views/account/login.php'; }
    public function register(): void { require __DIR__ . '/../../views/account/register.php'; }
    public function orders(): void { require __DIR__ . '/../../views/account/orders.php'; }
    public function profile(): void { require __DIR__ . '/../../views/account/profile.php'; }
    public function addresses(): void { require __DIR__ . '/../../views/account/addresses.php'; }
    public function orderDetail(): void { require __DIR__ . '/../../views/account/order-detail.php'; }
    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: main.php?r=home');
        exit();
    }
}
