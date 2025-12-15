<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'saphira');

// Tạo kết nối bằng MySQLi (Modern & an toàn hơn)
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    $conn = mysqli_connect('localhost', 'root', '', DB_NAME);
}
if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

// (Bạn không cần đóng kết nối ở đây, nó sẽ được giữ mở 
// để các file khác sử dụng và tự động đóng khi script kết thúc)

?>