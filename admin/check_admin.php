<?php
session_start();
$role = $_SESSION['user_role'] ?? ($_SESSION['vai_tro'] ?? null);
if (!isset($_SESSION['user_id']) || $role !== 'quan_tri') {
    $_SESSION['redirect_url'] = 'admin/admin.php';
    header('Location: ../main.php?r=login');
    exit();
}
$admin_username = $_SESSION['user_email'] ?? ($_SESSION['email'] ?? 'Admin');
?>
