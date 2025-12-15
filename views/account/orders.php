<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: main.php?r=login'); exit(); }
$user_id = (int)$_SESSION['user_id'];
include __DIR__ . '/../../config/db_connect.php';
$stmt_user = mysqli_prepare($conn, "SELECT ho_ten, email, anh_dai_dien FROM nguoi_dung WHERE ma_nguoi_dung = ?");
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$user = $result_user ? mysqli_fetch_assoc($result_user) : null;
$stmt_orders = mysqli_prepare($conn, "SELECT ma_don_hang, thoi_gian_dat, tong_tien, trang_thai_don FROM don_hang WHERE ma_nguoi_dung = ? ORDER BY thoi_gian_dat DESC");
mysqli_stmt_bind_param($stmt_orders, "i", $user_id);
mysqli_stmt_execute($stmt_orders);
$result_orders = mysqli_stmt_get_result($stmt_orders);
$orders = $result_orders ? mysqli_fetch_all($result_orders, MYSQLI_ASSOC) : [];
$page_title = 'Đơn Hàng Của Tôi';
$active_account_page = 'orders';
?>
<?php include __DIR__ . '/../partials/header_account.php'; ?>
<main class="account-main-container">
    <div class="account-page-header">
        <h1 class="account-page-title"><?php echo htmlspecialchars($page_title); ?></h1>
        <p class="account-page-subtitle">Xem lại lịch sử mua sắm của bạn</p>
    </div>
    <div class="account-layout-grid">
        <?php include __DIR__ . '/../partials/sidebar_account.php'; ?>
        <div class="account-content">
            <div class="content-panel">
                <h3 class="recent-orders-title">Lịch Sử Đơn Hàng</h3>
                <div class="table-container" style="margin-top: 20px;">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Mã Đơn Hàng</th>
                                <th>Ngày Đặt</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th class="col-text-right">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr><td colspan="5" style="text-align:center; padding:20px;">Bạn chưa có đơn hàng nào.</td></tr>
                            <?php else: foreach ($orders as $order): ?>
                                <?php 
                                    $status_text = $order['trang_thai_don'];
                                    $status_class = 'status-processing';
                                    switch ($order['trang_thai_don']) {
                                        case 'cho_xac_nhan': $status_text = 'Chờ xác nhận'; break;
                                        case 'da_xac_nhan': $status_text = 'Đã xác nhận'; break;
                                        case 'dang_giao': $status_text = 'Đang giao hàng'; $status_class = 'status-shipped'; break;
                                        case 'da_giao': $status_text = 'Hoàn thành'; $status_class = 'status-delivered'; break;
                                        case 'da_huy': $status_text = 'Đã hủy'; $status_class = 'status-cancelled'; break;
                                    }
                                ?>
                                <tr>
                                    <td class="col-text-light">#<?php echo htmlspecialchars($order['ma_don_hang']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($order['thoi_gian_dat'])); ?></td>
                                    <td class="col-text-light"><?php echo number_format($order['tong_tien']); ?>đ</td>
                                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                    <td class="col-text-right">
                                        <a href="main.php?r=order/detail&id=<?php echo htmlspecialchars($order['ma_don_hang']); ?>" class="btn-link" style="font-size: 14px; text-decoration: none;">Xem chi tiết</a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../partials/footer_account.php'; ?>
<?php mysqli_close($conn); ?>
