<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: main.php?r=login'); exit(); }
include __DIR__ . '/../../config/db_connect.php';
include __DIR__ . '/../../admin/csrf.php';
$user_id = (int)$_SESSION['user_id'];
$message = ''; $msg_type = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? null)) { $message = 'Phiên không hợp lệ'; $msg_type = 'error'; }
    else {
        $action = $_POST['action'] ?? '';
        if ($action === 'add') {
            $fullname = trim($_POST['fullname'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $district = trim($_POST['district'] ?? '');
            $ward = trim($_POST['ward'] ?? '');
            if ($fullname === '' || $phone === '' || $address === '') { $message = 'Vui lòng điền đủ thông tin'; $msg_type = 'error'; }
            else { $full_address = trim(implode(', ', array_filter([$address, $ward, $district, $city]))); $stmt = mysqli_prepare($conn, 'INSERT INTO dia_chi_giao_hang (ma_nguoi_dung, ho_ten_nguoi_nhan, so_dien_thoai, dia_chi_chi_tiet, mac_dinh) VALUES (?, ?, ?, ?, 0)'); mysqli_stmt_bind_param($stmt, 'isss', $user_id, $fullname, $phone, $full_address); if (mysqli_stmt_execute($stmt)) { $message = 'Đã thêm địa chỉ'; $msg_type = 'success'; } else { $message = 'Không thể thêm địa chỉ'; $msg_type = 'error'; } mysqli_stmt_close($stmt); }
        } elseif ($action === 'default') { $id = (int)($_POST['id'] ?? 0); $stmt1 = mysqli_prepare($conn, 'UPDATE dia_chi_giao_hang SET mac_dinh=0 WHERE ma_nguoi_dung=?'); mysqli_stmt_bind_param($stmt1, 'i', $user_id); $ok1 = mysqli_stmt_execute($stmt1); mysqli_stmt_close($stmt1); $stmt2 = mysqli_prepare($conn, 'UPDATE dia_chi_giao_hang SET mac_dinh=1 WHERE ma_dia_chi=? AND ma_nguoi_dung=?'); mysqli_stmt_bind_param($stmt2, 'ii', $id, $user_id); $ok2 = mysqli_stmt_execute($stmt2); mysqli_stmt_close($stmt2); if ($ok1 && $ok2) { $message = 'Đã đặt làm mặc định'; $msg_type = 'success'; } else { $message = 'Không thể cập nhật'; $msg_type = 'error'; } }
        elseif ($action === 'delete') { $id = (int)($_POST['id'] ?? 0); $stmt = mysqli_prepare($conn, 'DELETE FROM dia_chi_giao_hang WHERE ma_dia_chi=? AND ma_nguoi_dung=?'); mysqli_stmt_bind_param($stmt, 'ii', $id, $user_id); if (mysqli_stmt_execute($stmt)) { $message = 'Đã xóa địa chỉ'; $msg_type = 'success'; } else { $message = 'Không thể xóa địa chỉ'; $msg_type = 'error'; } mysqli_stmt_close($stmt); }
    }
}
$addresses = [];
$stmt = mysqli_prepare($conn, 'SELECT ma_dia_chi, ho_ten_nguoi_nhan, so_dien_thoai, dia_chi_chi_tiet, mac_dinh FROM dia_chi_giao_hang WHERE ma_nguoi_dung=? ORDER BY mac_dinh DESC, ma_dia_chi DESC');
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if ($res) { $addresses = mysqli_fetch_all($res, MYSQLI_ASSOC); }
mysqli_stmt_close($stmt);
$active_account_page = 'addresses';
$page_title = 'Địa chỉ giao hàng';
include __DIR__ . '/../partials/header_account.php';
?>
<main class="account-main-container">
    <div class="account-page-header"><h1 class="account-page-title">Địa Chỉ Giao Hàng</h1><p class="account-page-subtitle">Quản lý sổ địa chỉ của bạn</p></div>
    <div class="account-layout-grid">
        <?php include __DIR__ . '/../partials/sidebar_account.php'; ?>
        <div class="account-content">
            <?php if ($msg_type === 'error'): ?><div style="background:#B00020;color:#fff;padding:10px 12px;border-radius:8px;margin-bottom:12px;"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
            <?php if ($msg_type === 'success'): ?><div style="background:#0E7C0E;color:#fff;padding:10px 12px;border-radius:8px;margin-bottom:12px;"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
            <div class="content-panel">
                <h2 class="content-title">Sổ địa chỉ</h2>
                <div class="table-container"><table class="orders-table"><thead><tr><th>Người nhận</th><th>SĐT</th><th>Địa chỉ</th><th>Trạng thái</th><th>Hành động</th></tr></thead><tbody><?php if (empty($addresses)): ?><tr><td colspan="5" style="text-align:center;padding:16px;">Chưa có địa chỉ</td></tr><?php else: foreach ($addresses as $addr): ?><tr><td class="col-text-light"><?php echo htmlspecialchars($addr['ho_ten_nguoi_nhan']); ?></td><td><?php echo htmlspecialchars($addr['so_dien_thoai']); ?></td><td><?php echo htmlspecialchars($addr['dia_chi_chi_tiet']); ?></td><td><?php echo ((int)$addr['mac_dinh'] === 1) ? 'Mặc định' : ''; ?></td><td><form method="POST" style="display:inline-block;"><?php echo csrf_input(); ?><input type="hidden" name="action" value="default" /><input type="hidden" name="id" value="<?php echo (int)$addr['ma_dia_chi']; ?>" /><button class="btn-link" type="submit">Đặt làm mặc định</button></form><form method="POST" style="display:inline-block;margin-left:8px;" onsubmit="return confirm('Xóa địa chỉ này?');"><?php echo csrf_input(); ?><input type="hidden" name="action" value="delete" /><input type="hidden" name="id" value="<?php echo (int)$addr['ma_dia_chi']; ?>" /><button class="btn-link" type="submit">Xóa</button></form></td></tr><?php endforeach; endif; ?></tbody></table></div>
                <h3 class="content-title" style="margin-top:20px;">Thêm địa chỉ mới</h3>
                <form method="POST" style="max-width:800px;"><?php echo csrf_input(); ?><input type="hidden" name="action" value="add" /><div class="form-grid"><div class="col-span-2"><label class="form-label">Quốc gia</label><select class="form-input" name="country" id="countrySelect" required><option value="Vietnam" selected>Vietnam</option></select></div><div class="col-span-2"><label class="form-label">Họ và tên</label><input class="form-input" name="fullname" type="text" placeholder="Họ và tên" required /></div><div class="col-span-2"><label class="form-label">Số điện thoại</label><input class="form-input" name="phone" type="text" placeholder="Số điện thoại" required /></div><div class="col-span-2"><label class="form-label">Địa chỉ</label><input class="form-input" name="address" type="text" placeholder="Số nhà, tên đường..." required /></div><div class="col-span-2" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;"><div><label class="form-label">Tỉnh / thành</label><select class="form-input" id="citySelect" name="city" required><option value="">Chọn tỉnh / thành</option></select></div><div><label class="form-label">Quận / huyện</label><select class="form-input" id="districtSelect" name="district" required><option value="">Chọn quận / huyện</option></select></div><div><label class="form-label">Phường / xã</label><select class="form-input" id="wardSelect" name="ward" required><option value="">Chọn phường / xã</option></select></div></div></div><div style="display:flex; gap:12px; margin-top:12px;"><button class="btn-primary" type="submit" style="height:40px; padding:0 16px;">Lưu địa chỉ mới</button><a class="btn-secondary" href="main.php?r=addresses" style="height:40px; padding:0 16px;">Hủy</a></div></form>
            </div>
        </div>
    </div>
</main>
<script>document.addEventListener('DOMContentLoaded', function(){ const citySel = document.getElementById('citySelect'); const distSel = document.getElementById('districtSelect'); const wardSel = document.getElementById('wardSelect'); const API_BASE = 'https://esgoo.net/api-tinhthanh'; async function fetchJson(url){ try { const r = await fetch(url); const j = await r.json(); return Array.isArray(j.data) ? j.data : []; } catch(e){ return []; } } function setOptions(sel, arr){ sel.innerHTML = '<option value="">Chọn</option>' + arr.map(x => `<option data-id="${x.id}">${x.name||''}</option>`).join(''); sel.value=''; } async function loadProvinces(){ const provinces = await fetchJson(`${API_BASE}/1/0.htm`); setOptions(citySel, provinces); } async function onCity(){ wardSel.innerHTML='<option value="">Chọn</option>'; distSel.innerHTML='<option value="">Chọn</option>'; const opt = citySel.options[citySel.selectedIndex]; const pid = opt && opt.dataset.id; if(!pid) return; const districts = await fetchJson(`${API_BASE}/2/${pid}.htm`); setOptions(distSel, districts); } async function onDistrict(){ const opt = distSel.options[distSel.selectedIndex]; const did = opt && opt.dataset.id; if(!did) return; const wards = await fetchJson(`${API_BASE}/3/${did}.htm`); setOptions(wardSel, wards); } loadProvinces(); citySel.addEventListener('change', onCity); distSel.addEventListener('change', onDistrict); });</script>
<?php include __DIR__ . '/../partials/footer_account.php'; ?>
