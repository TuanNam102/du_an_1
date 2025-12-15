<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: main.php?r=login'); exit(); }
$user_id = $_SESSION['user_id'];
include __DIR__ . '/../../config/db_connect.php';
$sql_user = "SELECT ho_ten, email FROM nguoi_dung WHERE ma_nguoi_dung = $user_id";
$result_user = mysqli_query($conn, $sql_user);
$user = mysqli_fetch_assoc($result_user);
$sql_orders = "SELECT ma_don_hang, thoi_gian_dat, tong_tien, trang_thai_don FROM don_hang WHERE ma_nguoi_dung = $user_id ORDER BY thoi_gian_dat DESC LIMIT 3";
$result_orders = mysqli_query($conn, $sql_orders);
$orders = mysqli_fetch_all($result_orders, MYSQLI_ASSOC);
$page_title = 'Tổng quan tài khoản';
$active_account_page = 'overview';
?>
<?php include __DIR__ . '/../partials/header_account.php'; ?>
<main class="account-main-container">
    <div class="account-page-header"><h1 class="account-page-title">Tài Khoản Của Bạn</h1><p class="account-page-subtitle">Quản lý thông tin và đơn hàng của bạn</p></div>
    <div class="account-layout-grid">
        <?php include __DIR__ . '/../partials/sidebar_account.php'; ?>
        <div class="account-content">
            <div class="content-panel" id="overview-content">
                <h2 class="content-title">Tổng quan tài khoản</h2>
                <p class="content-description">Chào mừng trở lại, <?php echo htmlspecialchars($user['ho_ten']); ?>! Từ đây bạn có thể xem các đơn hàng gần đây, quản lý địa chỉ giao hàng và chỉnh sửa thông tin cá nhân.</p>
                <div class="recent-orders-section">
                    <h3 class="recent-orders-title">Đơn hàng gần đây</h3>
                    <div class="table-container">
                        <table class="orders-table"><thead><tr><th>Đơn hàng</th><th class="col-hidden-sm">Ngày đặt</th><th>Trạng thái</th><th class="col-text-right">Tổng tiền</th></tr></thead><tbody>
                            <?php if (empty($orders)): ?>
                            <tr><td colspan="4" style="text-align: center; padding: 20px;">Bạn chưa có đơn hàng nào.</td></tr>
                            <?php else: foreach ($orders as $order): $status_class = 'status-processing'; $status_text = $order['trang_thai_don']; switch ($order['trang_thai_don']) { case 'cho_xac_nhan': $status_text = 'Chờ xác nhận'; $status_class = 'status-processing'; break; case 'da_xac_nhan': $status_text = 'Đã xác nhận'; $status_class = 'status-processing'; break; case 'dang_giao': $status_text = 'Đang giao'; $status_class = 'status-shipped'; break; case 'da_giao': $status_text = 'Hoàn thành'; $status_class = 'status-delivered'; break; case 'da_huy': $status_text = 'Đã hủy'; $status_class = 'status-cancelled'; break; } ?>
                            <tr>
                                <td>#<?php echo $order['ma_don_hang']; ?></td>
                                <td class="col-hidden-sm"><?php echo date('d/m/Y', strtotime($order['thoi_gian_dat'])); ?></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                <td class="col-text-right"><?php echo number_format($order['tong_tien']); ?>₫</td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody></table>
                    </div>
                    <div class="view-all-orders"><a href="main.php?r=orders" class="btn-link">Xem tất cả đơn hàng</a></div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../partials/footer_account.php'; ?>
<?php mysqli_close($conn); ?>
