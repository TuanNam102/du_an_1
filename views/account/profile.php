<?php
session_start();
include 'config/db_connect.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: main.php?r=login');
    exit();
}
$user_id = $_SESSION['user_id'];
$message = '';
$msg_type = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ho_ten = mysqli_real_escape_string($conn, $_POST['fullname']);
    $sdt = mysqli_real_escape_string($conn, $_POST['phone']);
    $avatar_sql = "";
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $target_dir = "public/img/avatars/";
        $extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
        $new_filename = "avatar_" . $user_id . "_" . time() . "." . $extension;
        $target_file = $target_dir . $new_filename;
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array(strtolower($extension), $allowed)) {
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                $db_path = "/img/avatars/" . $new_filename;
                $avatar_sql = ", anh_dai_dien = '$db_path'";
            } else {
                $message = "Lỗi khi tải ảnh lên.";
                $msg_type = "error";
            }
        } else {
            $message = "Chỉ chấp nhận file ảnh (JPG, PNG, WEBP).";
            $msg_type = "error";
        }
    }
    if (empty($message)) {
        $sql_update = "UPDATE nguoi_dung SET ho_ten = '$ho_ten', so_dien_thoai = '$sdt' $avatar_sql WHERE ma_nguoi_dung = $user_id";
        if (mysqli_query($conn, $sql_update)) {
            $message = "Cập nhật thông tin thành công!";
            $msg_type = "success";
            $_SESSION['user_name'] = $ho_ten;
        } else {
            $message = "Lỗi hệ thống: " . mysqli_error($conn);
            $msg_type = "error";
        }
    }
}
$sql_user = "SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = $user_id";
$result = mysqli_query($conn, $sql_user);
$user = mysqli_fetch_assoc($result);
$avatar_display = "public/img/avatar-placeholder.jpg";
if (!empty($user['anh_dai_dien'])) {
    $avatar_display = "public" . $user['anh_dai_dien'];
}
$page_title = 'Thông Tin Cá Nhân';
$active_account_page = 'details';
?>
<?php include __DIR__ . '/../partials/header_account.php'; ?>
<main class="account-main-container">
    <div class="account-page-header">
        <h1 class="account-page-title">Hồ Sơ Của Tôi</h1>
        <p class="account-page-subtitle">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
    </div>
    <div class="account-layout-grid">
        <?php include __DIR__ . '/../partials/sidebar_account.php'; ?>
        <div class="account-content">
            <div class="content-panel">
                <h2 class="content-title">Thông Tin Cá Nhân</h2>
                <div class="content-description" style="margin-bottom: 20px;">Thay đổi thông tin nhận dạng và liên lạc
                    của bạn.</div><?php if ($message): ?>
                    <div
                        style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: <?php echo $msg_type == 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $msg_type == 'success' ? '#155724' : '#721c24'; ?>;">
                        <?php echo $message; ?></div><?php endif; ?>
                <form action="profile.php" method="POST" enctype="multipart/form-data"
                    style="display: flex; flex-wrap: wrap; gap: 40px;">
                    <div style="flex: 1; min-width: 300px;">
                        <div class="form-group" style="margin-bottom: 20px;"><label
                                style="display: block; margin-bottom: 8px; color: var(--color-text-dim); font-weight: 500;">Tên
                                đăng nhập / Email</label>
                            <div style="display: flex; align-items: center; gap: 10px;"><input type="text"
                                    value="<?php echo htmlspecialchars($user['email']); ?>" disabled
                                    style="width: 100%; padding: 12px; background: #333; border: 1px solid #444; color: #aaa; border-radius: 8px;"><span
                                    class="material-symbols-outlined" style="color: var(--color-green);"
                                    title="Đã xác minh">check_circle</span></div><small style="color: #666;">Email không
                                thể thay đổi.</small>
                        </div>
                        <div class="form-group" style="margin-bottom: 20px;"><label for="fullname"
                                style="display: block; margin-bottom: 8px; color: var(--color-text-light); font-weight: 500;">Họ
                                và Tên</label><input type="text" id="fullname" name="fullname"
                                value="<?php echo htmlspecialchars($user['ho_ten']); ?>" required
                                style="width: 100%; padding: 12px; background: transparent; border: 1px solid var(--color-border); color: var(--color-text-light); border-radius: 8px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 30px;"><label for="phone"
                                style="display: block; margin-bottom: 8px; color: var(--color-text-light); font-weight: 500;">Số
                                điện thoại</label><input type="tel" id="phone" name="phone"
                                value="<?php echo htmlspecialchars($user['so_dien_thoai']); ?>"
                                style="width: 100%; padding: 12px; background: transparent; border: 1px solid var(--color-border); color: var(--color-text-light); border-radius: 8px;">
                        </div><button type="submit" class="btn-primary-gold"
                            style="padding: 12px 30px; cursor: pointer;">Lưu Thay Đổi</button>
                    </div>
                    <div
                        style="width: 250px; text-align: center; border-left: 1px solid var(--color-border); padding-left: 40px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div
                            style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin-bottom: 20px; border: 2px solid var(--color-primary);">
                            <img id="avatar-preview" src="<?php echo $avatar_display; ?>" alt="Avatar"
                                style="width: 100%; height: 100%; object-fit: cover;"></div><label for="avatar-upload"
                            style="cursor: pointer; background: #333; color: white; padding: 8px 15px; border-radius: 4px; font-size: 14px; border: 1px solid #555; transition: 0.3s;">Chọn
                            Ảnh</label><input type="file" id="avatar-upload" name="avatar" accept="image/*"
                            style="display: none;" onchange="previewImage(this)">
                        <p style="color: #666; font-size: 12px; margin-top: 10px;">Dụng lượng file tối đa 1 MB.<br>Định
                            dạng: .JPEG, .PNG</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>function previewImage(input) { if (input.files && input.files[0]) { var reader = new FileReader(); reader.onload = function (e) { document.getElementById('avatar-preview').src = e.target.result; }; reader.readAsDataURL(input.files[0]); } }</script>
<?php include __DIR__ . '/../partials/footer_account.php'; ?>
<?php mysqli_close($conn); ?>