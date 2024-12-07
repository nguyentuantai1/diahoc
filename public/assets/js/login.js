
      // Điều hướng quay lại trang chủ
      document.getElementById("back-home").addEventListener("click", () => {
        window.location.href = "http://127.0.0.1:5500/home.html";
      });

      // Xử lý đăng nhập
      document
        .getElementById("login-form")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const username = document.getElementById("login-username").value;
          const password = document.getElementById("login-password").value;
          const storedUser = localStorage.getItem("accounts");

          if (storedUser) {
            const accounts = JSON.parse(storedUser);
            const user = accounts.find(
              (account) => account.username === username
            );

            if (user) {
              if (user.password === password) {
                sessionStorage.setItem("isLoggedIn", true);
                sessionStorage.setItem("username", username); // Lưu tên tài khoản vào sessionStorage
                window.location.href = "http://127.0.0.1:5500/home.html"; // Chuyển hướng đến trang chủ
              } else {
                alert("Mật khẩu không đúng. Vui lòng thử lại.");
              }
            } else {
              alert("Tên đăng nhập không tồn tại. Vui lòng đăng ký.");
            }
          } else {
            alert("Chưa có tài khoản nào được lưu. Vui lòng đăng ký.");
          }
        });
   