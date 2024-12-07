<?php
session_start();

// Xóa tất cả các session
session_unset();

// Hủy session
session_destroy();

// Xóa thông tin đăng nhập trong localStorage của trình duyệt
echo '<script>
        localStorage.setItem("logged_in", "false");
        window.location.href = "http://localhost/DIAHOIC/main.php"; // Chuyển hướng về trang chính hoặc trang đăng nhập
      </script>';
?>
