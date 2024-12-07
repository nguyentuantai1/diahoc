<?php
// Thông tin kết nối cơ sở dữ liệu
$host = 'localhost'; // Tên máy chủ MySQL
$db = 'hospital_management'; // Tên cơ sở dữ liệu
$user = 'root'; // Tên người dùng MySQL (mặc định là 'root')
$password = ''; // Mật khẩu MySQL (mặc định là trống)

// Tạo kết nối sử dụng PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
    // Thiết lập chế độ báo lỗi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Nếu xảy ra lỗi, thông báo và dừng chương trình
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}
?>
