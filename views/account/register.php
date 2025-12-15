<?php
session_start();
include 'config/db_connect.php';
if (isset($_SESSION['user_id'])) {
    header('Location: main.php?r=home');
    exit();
}
$ho_ten = '';
$email = '';
$sdt = '';
$contact = '';
$errors = [];
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ho_ten = isset($_POST['fullname']) ? mysqli_real_escape_string($conn, $_POST['fullname']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $password = $_POST['password'];
    $confirm_password = $_POST['password_confirm'];
    if (empty($contact)) {
        $errors[] = 'Vui lòng nhập số điện thoại hoặc email.';
    } else {
        if (strpos($contact, '@') !== false) {
            $email = mysqli_real_escape_string($conn, $contact);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ.';
            } else {
                $stmt_check = mysqli_prepare($conn, "SELECT ma_nguoi_dung FROM nguoi_dung WHERE email = ?");
                mysqli_stmt_bind_param($stmt_check, "s", $email);
                mysqli_stmt_execute($stmt_check);
                $result = mysqli_stmt_get_result($stmt_check);
                if ($result && mysqli_num_rows($result) > 0) {
                    $errors[] = 'Email này đã được đăng ký.';
                }
            }
        } else {
            $sdt = mysqli_real_escape_string($conn, $contact);
            if (!preg_match('/^[0-9\+\-\s]{9,15}$/', $sdt)) {
                $errors[] = 'Số điện thoại không hợp lệ.';
            }
        }
    }
    if (empty($password))
        $errors[] = 'Vui lòng nhập mật khẩu.';
    if ($password !== $confirm_password)
        $errors[] = 'Mật khẩu xác nhận không khớp.';
    if (empty($errors)) {
        if (empty($ho_ten)) { $ho_ten = 'Khách'; }
        $hashed_password = md5($password);
        $stmt_ins = mysqli_prepare($conn, "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, vai_tro, trang_thai) VALUES (?, ?, ?, ?, 'khach_hang', 1)");
        mysqli_stmt_bind_param($stmt_ins, "ssss", $ho_ten, $email, $hashed_password, $sdt);
        if (mysqli_stmt_execute($stmt_ins)) {
            $success_message = 'Đăng ký thành công!';
            $ho_ten = '';
            $email = '';
            $sdt = '';
            $contact = '';
        } else {
            $errors[] = 'Lỗi hệ thống: ' . mysqli_error($conn);
        }
    }
}
mysqli_close($conn);
$page_title = 'Đăng Ký';
?>
<?php include __DIR__ . '/../partials/header_auth.php'; ?>
<main class="login-container">
    <div class="login-box">
        <div class="login-box-header">
            <p class="login-title">Đăng Ký Tài Khoản</p>
            <p class="login-subtitle">Tạo tài khoản để bắt đầu mua sắm</p>
        </div>
        <?php if (!empty($errors)): ?>
            <div
                style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php foreach ($errors as $error):
                    echo "<p>$error</p>"; endforeach; ?></div><?php endif; ?>
        <?php if ($success_message): ?>
            <div
                style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align:center; font-weight:600;">
                <?php echo $success_message; ?>
            </div>
            <script>setTimeout(function(){ window.location.href = 'main.php?r=login'; }, 1500);</script>
        <?php endif; ?>
        <form class="login-form" action="main.php?r=register" method="POST">
            <input type="hidden" name="fullname" value="<?php echo htmlspecialchars($ho_ten ?: 'Khách'); ?>" />
            <div class="form-group">
                <p class="form-label">Số điện thoại hoặc email</p><input class="form-input" placeholder="Nhập số điện thoại hoặc email" type="text"
                    name="contact" value="<?php echo htmlspecialchars($contact); ?>" required />
            </div>
            <div class="form-group">
                <p class="form-label">Mật khẩu</p><input class="form-input" placeholder="Nhập mật khẩu" type="password"
                    name="password" required />
            </div>
            <div class="form-group">
                <p class="form-label">Xác nhận Mật khẩu</p><input class="form-input" placeholder="Nhập lại mật khẩu"
                    type="password" name="password_confirm" required />
            </div>
            <label class="form-checkbox-wrapper"><input class="form-checkbox" type="checkbox" name="terms"
                    required /><span>Tôi đồng ý với <a href="#" class="form-link-signup">Điều khoản & Điều
                        kiện</a></span></label>
            <button class="btn-primary-login" type="submit">Đăng ký</button>
        </form>
        <p class="signup-link-container">Đã có tài khoản? <a href="main.php?r=login" class="form-link-signup">Đăng nhập
                ngay</a></p>
    </div>
</main>
<?php include __DIR__ . '/../partials/footer_auth.php'; ?>
