<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "hospital_management");

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["login-username"];
    $password = $_POST["login-password"];

    // Debug các giá trị từ form
    echo "Username: " . $username . "<br>";
    echo "Password: " . $password . "<br>";

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    echo "SQL Query: " . $sql . "<br>"; // Log câu truy vấn SQL

    $result = mysqli_query($mysqli, $sql);

    // Debug kết quả truy vấn
    if ($result) {
        echo "Query executed successfully.<br>";
        echo "Number of rows: " . mysqli_num_rows($result) . "<br>";
    } else {
        echo "Query failed: " . mysqli_error($mysqli) . "<br>";
    }

    if (mysqli_num_rows($result) > 0) {
        $_SESSION["username"] = $username;
        $_SESSION['logged_in'] = true;
        echo "danh nhap thanh cong";
        header("location:http://localhost/DIAHOIC/main.php");
    } else {
        echo "No matching user found.<br>";
    }
}


?>


<!-- Form đăng nhập -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="http://localhost/DIAHOIC/public/assets/css/user.css" />
    <!-- <script src="http://localhost/DIAHOIC/public/assets/js/user.js"></script> -->
</head>

<body>
    <section id="auth" class="section">
        <div class="auth-box">
            <h2>Đăng nhập</h2>
            <form id="login-form" action="" method="POST">
                <label for="login-username">Tên đăng nhập:</label>
                <input
                    type="text"
                    id="login-username"
                    name="login-username"
                    placeholder="Nhập tên đăng nhập"
                    required />

                <label for="login-password">Mật khẩu:</label>
                <input
                    type="password"
                    id="login-password"
                    name="login-password"
                    placeholder="Nhập mật khẩu"
                    required />

                <button type="submit">Đăng nhập</button>
            </form>
            <button id="back-home" class="secondary-btn">Quay lại trang chủ</button>
        </div>
    </section>

    <script>
        // Điều hướng quay lại trang chủ
        document.getElementById("back-home").addEventListener("click", () => {
            window.location.href = "http://localhost/DIAHOIC/main.php";
        });

        //   // Xử lý đăng nhập
        //   document
        //     .getElementById("login-form")
        //     .addEventListener("submit", function (e) {
        //       e.preventDefault();

        //       const username = document.getElementById("login-username").value;
        //       const password = document.getElementById("login-password").value;
        //       const storedUser = localStorage.getItem("accounts");

        //       if (storedUser) {
        //         const accounts = JSON.parse(storedUser);
        //         const user = accounts.find(
        //           (account) => account.username === username
        //         );

        //         if (user) {
        //           if (user.password === password) {
        //             sessionStorage.setItem("isLoggedIn", true);
        //             sessionStorage.setItem("username", username); // Lưu tên tài khoản vào sessionStorage
        //             window.location.href = "http://localhost/DIAHOIC/main.php"; // Chuyển hướng đến trang chủ
        //           } else {
        //             alert("Mật khẩu không đúng. Vui lòng thử lại.");
        //           }
        //         } else {
        //           alert("Tên đăng nhập không tồn tại. Vui lòng đăng ký.");
        //         }
        //       } else {
        //         alert("Chưa có tài khoản nào được lưu. Vui lòng đăng ký.");
        //       }
        //     });
    </script>

</body>

</html>