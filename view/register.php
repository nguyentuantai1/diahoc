<?php
session_start();
$conn = new mysqli("localhost", "root", "", "hospital_management");

// Check connection
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['register-username'];
    $password = $_POST['register-password'];
    $email = $_POST['register-email'];

    // Vai trò mặc định là 'user'
    $role = 'user';

    // Chèn người dùng vào cơ sở dữ liệu
    $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $password, $email, $role);

    if ($stmt->execute()) {
        header("location:http://localhost/DIAHOIC/view/login.php");
    } else {
        echo "Lỗi: " . $stmt->error;
    }
    
}
?>
<!-- Form đăng ký -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký</title>
    <link rel="stylesheet" href="http://localhost/DIAHOIC/public/assets/css/user.css" />
    <script src="http://localhost/DIAHOIC/public/assets/js/user.js"></script>
  </head>
  <body>
    <section id="auth" class="section">
      <div class="auth-box">
        <h2>Đăng ký</h2>
        <form id="register-form"  action="" method="POST">
          <label for="register-username">Tên đăng nhập:</label>
          <input
            type="text"
            id="register-username"
            name="register-username"
            placeholder="Nhập tên đăng nhập"
            required
          />

          <label for="register-email">Email:</label>
          <input
            type="email"
            id="register-email"
            name="register-email"
            placeholder="Nhập email"
            required
          />

          <label for="register-password">Mật khẩu:</label>
          <input
            type="password"
            id="register-password"
            name="register-password"
            placeholder="Nhập mật khẩu"
            required
          />

          <button type="submit">Đăng ký</button>
        </form>
        <button id="back-home" class="secondary-btn">Quay lại trang chủ</button>
      </div>
    </section>

   
  </body>
</html>
