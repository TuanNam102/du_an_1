<?php
session_start();
include __DIR__ . '/../../config/db_connect.php';
include_once __DIR__ . '/../../config/helpers.php';
if (!isset($_SESSION['user_id'])) { header('Location: main.php?r=login'); exit(); }
$user_id = (int)$_SESSION['user_id'];
if (!isset($_GET['id'])) { header('Location: main.php?r=account'); exit(); }
$order_id = (int)$_GET['id'];
$stmt_order = mysqli_prepare($conn, "SELECT dh.ma_don_hang, dh.thoi_gian_dat, dh.tong_tien, dc.ho_ten_nguoi_nhan, dc.so_dien_thoai, dc.dia_chi_chi_tiet, dh.trang_thai_don FROM don_hang dh LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi_giao_hang = dc.ma_dia_chi WHERE dh.ma_don_hang = ? AND dh.ma_nguoi_dung = ?");
mysqli_stmt_bind_param($stmt_order, "ii", $order_id, $user_id);
mysqli_stmt_execute($stmt_order);
$result_order = mysqli_stmt_get_result($stmt_order);
$order = $result_order ? mysqli_fetch_assoc($result_order) : null;
if (!$order) { header('Location: main.php?r=account'); exit(); }
$sql_items = "SELECT ct.so_luong, ct.don_gia, ct.ten_nuoc_hoa, b.dung_tich_ml, MIN(h.duong_dan_hinh) as hinh_anh FROM chi_tiet_don_hang ct JOIN bien_the_nuoc_hoa b ON ct.ma_bien_the = b.ma_bien_the JOIN nuoc_hoa n ON b.ma_nuoc_hoa = n.ma_nuoc_hoa LEFT JOIN hinh_anh_nuoc_hoa h ON n.ma_nuoc_hoa = h.ma_nuoc_hoa WHERE ct.ma_don_hang = ? AND (h.loai_hinh_anh = 'anh_lon' OR h.loai_hinh_anh IS NULL) GROUP BY ct.ma_chi_tiet";
$stmt_items = mysqli_prepare($conn, $sql_items);
mysqli_stmt_bind_param($stmt_items, "i", $order_id);
mysqli_stmt_execute($stmt_items);
$result_items = mysqli_stmt_get_result($stmt_items);
$order_items = $result_items ? mysqli_fetch_all($result_items, MYSQLI_ASSOC) : [];
mysqli_close($conn);
?>
<?php include __DIR__ . '/../partials/header_order_detail.php'; ?>
<main class="order-main-container"><div class="order-content-wrapper"><div class="order-page-header"><div class="order-icon-wrapper"><span class="material-symbols-outlined">check</span></div><h1 class="order-page-title">Đơn Hàng Đã Được Xác Nhận!</h1><p class="order-page-subtitle">Cảm ơn bạn đã mua sắm tại SAPHIRA. Chúng tôi sẽ sớm liên hệ để giao hàng.</p></div><div class="order-layout-grid"><div class="order-details-box"><h3 class="box-title">Chi Tiết Đơn Hàng</h3><div class="order-info-list"><div class="order-info-row"><span>Mã đơn hàng:</span><span class="order-id">#<?php echo htmlspecialchars($order['ma_don_hang']); ?></span></div><div class="order-info-row"><span>Ngày đặt hàng:</span><span><?php echo date('d/m/Y H:i', strtotime($order['thoi_gian_dat'])); ?></span></div><div class="order-info-row"><span>Trạng thái:</span><span style="color: var(--color-primary); font-weight: bold;"><?php $status_map=['cho_xac_nhan'=>'Chờ xác nhận','da_xac_nhan'=>'Đã xác nhận','dang_giao'=>'Đang giao hàng','da_giao'=>'Hoàn thành','da_huy'=>'Đã hủy']; echo $status_map[$order['trang_thai_don']] ?? $order['trang_thai_don']; ?></span></div></div><div class="divider"></div><div class="order-items-list"><?php foreach ($order_items as $item): ?><div class="order-item"><img class="order-item-image" alt="<?php echo htmlspecialchars($item['ten_nuoc_hoa']); ?>" src="<?php echo htmlspecialchars(asset($item['hinh_anh'])); ?>" /><div class="order-item-info"><p class="order-item-name"><?php echo htmlspecialchars($item['ten_nuoc_hoa']); ?></p><span class="order-item-meta"><?php echo $item['dung_tich_ml']; ?>ml x <?php echo $item['so_luong']; ?></span></div><div class="order-item-price"><p><?php echo number_format($item['don_gia'] * $item['so_luong']); ?>₫</p><span>Đơn giá: <?php echo number_format($item['don_gia']); ?>₫</span></div></div><?php endforeach; ?></div><div class="divider"></div><div class="order-total-row"><span>Tổng cộng</span><span class="total-price"><?php echo number_format($order['tong_tien']); ?>₫</span></div></div><div class="shipping-info-box"><h3 class="box-title">Thông Tin Giao Hàng</h3><div class="shipping-info-list"><p><span>Người nhận:</span> <?php echo htmlspecialchars($order['ho_ten_nguoi_nhan']); ?></p><p><span>Số điện thoại:</span> <?php echo htmlspecialchars($order['so_dien_thoai']); ?></p><p><span>Địa chỉ:</span> <?php echo htmlspecialchars($order['dia_chi_chi_tiet']); ?></p><p><span>Vận chuyển:</span> Giao hàng tiêu chuẩn</p><p class="delivery-estimate"><span>Dự kiến giao hàng:</span><span class="delivery-date">3 - 5 ngày tới</span></p></div></div></div><div class="order-actions"><a href="main.php?r=orders" class="btn-outline-gold" style="text-decoration: none;">Xem Đơn Hàng Của Tôi</a><a href="main.php?r=home" class="btn-primary-gold" style="text-decoration: none;">Tiếp tục mua sắm</a></div></div></main>
<?php if (isset($_GET['clear']) && (int)$_GET['clear'] === 1): ?>
<script>(function(){ try { localStorage.removeItem('saphira_cart'); localStorage.removeItem('cart'); localStorage.removeItem('cart_items'); var cc = document.querySelector('.cart-count'); if (cc) cc.textContent = '0'; } catch (e) {} })();</script>
<?php endif; ?>
<?php include __DIR__ . '/../partials/footer_order_detail.php'; ?>
