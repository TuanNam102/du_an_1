<?php
session_start();
include 'config/db_connect.php';
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'quan_tri') {
        header('Location: admin/admin.php');
    } else {
        header('Location: main.php?r=home');
    }
    exit();
}
$email = '';
$password = '';
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email'])) {
        $email = trim($_POST['email']);
    }
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
    }
    if (empty($email) || empty($password)) {
        $error_message = 'Vui lòng nhập email và mật khẩu.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM nguoi_dung WHERE email = ? AND trang_thai = 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (md5($password) === $user['mat_khau']) {
                $_SESSION['user_id'] = $user['ma_nguoi_dung'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['ho_ten'];
                $_SESSION['user_role'] = $user['vai_tro'];
                if ($user['vai_tro'] === 'quan_tri') {
                    header('Location: main.php?r=admin/dashboard');
                } else {
                    if (isset($_SESSION['redirect_url'])) {
                        $url = $_SESSION['redirect_url'];
                        unset($_SESSION['redirect_url']);
                        header("Location: $url");
                    } else {
                        header('Location: main.php?r=home');
                    }
                }
                exit();
            } else {
                $error_message = 'Mật khẩu không chính xác.';
            }
        } else {
            $stmt2 = mysqli_prepare($conn, "SELECT TenDN, Email, HoTen, QuyenTC, TrangThai, MatKhau FROM tai_khoan WHERE Email = ? AND TrangThai = 1");
            mysqli_stmt_bind_param($stmt2, "s", $email);
            mysqli_stmt_execute($stmt2);
            $result2 = mysqli_stmt_get_result($stmt2);
            if ($result2 && mysqli_num_rows($result2) == 1) {
                $legacy = mysqli_fetch_assoc($result2);
                if ((int) $legacy['QuyenTC'] === 1 && md5($password) === $legacy['MatKhau']) {
                    $_SESSION['ten_dn'] = $legacy['TenDN'];
                    $_SESSION['quyen_tc'] = 1;
                    $_SESSION['user_name'] = $legacy['HoTen'] ?? 'Admin';
                    $_SESSION['user_email'] = $legacy['Email'];
                    $_SESSION['user_role'] = 'quan_tri';
                    header('Location: main.php?r=admin/dashboard');
                    exit();
                }
            }
            $error_message = 'Email không tồn tại hoặc tài khoản bị khóa.';
        }
        mysqli_free_result($result);
    }
}
mysqli_close($conn);
$page_title = 'Đăng Nhập';
?>
<?php include __DIR__ . '/../partials/header_auth.php'; ?>
<main class="login-container">
    <div class="login-box">
        <div class="login-box-header">
            <p class="login-title">Đăng Nhập SAPHIRA</p>
            <p class="login-subtitle">Truy cập tài khoản để trải nghiệm mua sắm</p>
        </div>
        <?php if ($error_message): ?>
            <div
                style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($error_message); ?></div><?php endif; ?>
        <form class="login-form" action="main.php?r=login" method="POST">
            <div class="form-group">
                <p class="form-label">Email</p><input class="form-input" placeholder="Nhập địa chỉ email" type="email"
                    name="email" value="<?php echo htmlspecialchars($email); ?>" required />
            </div>
            <div class="form-group">
                <div class="form-label-row">
                    <p class="form-label">Mật khẩu</p><a href="#" class="form-link-forgot">Quên mật khẩu?</a>
                </div><input class="form-input" placeholder="Nhập mật khẩu" type="password" name="password" required />
            </div>
            <button class="btn-primary-login" type="submit">Đăng nhập</button>
        </form>
        <div class="social-login-container"></div>
        <p class="signup-link-container">Chưa có tài khoản? <a href="main.php?r=register" class="form-link-signup">Đăng
                ký ngay</a></p>
    </div>
</main>
<?php include __DIR__ . '/../partials/footer_auth.php'; ?>