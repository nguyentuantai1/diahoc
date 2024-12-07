const isLoggedIn = sessionStorage.getItem("isLoggedIn");

// Kiểm tra trạng thái đăng nhập và thay đổi giao diện phù hợp
if (isLoggedIn) {
  // Ẩn mục đăng nhập và đăng ký
  document.getElementById("login-link").style.display = "none";
  document.getElementById("register-link").style.display = "none";

  // Hiển thị mục user với các tùy chọn
  document.getElementById("user-link").style.display = "block";

  // Lắng nghe sự kiện đăng xuất
  document.getElementById("logout").addEventListener("click", () => {
    sessionStorage.clear(); // Xóa toàn bộ dữ liệu đăng nhập
    window.location.href = "http://localhost/DIAHOIC/main.php"; // Chuyển hướng về trang chủ sau khi đăng xuất
  });

  // Lắng nghe sự kiện xem thông tin tài khoản
  document.getElementById("view-profile").addEventListener("click", () => {
    const username = sessionStorage.getItem("username");
    const user = JSON.parse(localStorage.getItem(username));

    // Hiển thị thông tin tài khoản trong popup
    document.getElementById("profile-username").textContent = user.username;
    document.getElementById("profile-password").value = user.password;

    // Mở popup
    document.getElementById("profile-popup").style.display = "flex";
  });

  // Toggle hiển thị mật khẩu
  document.getElementById("toggle-password").addEventListener("click", () => {
    const passwordField = document.getElementById("profile-password");
    const passwordType = passwordField.type === "password" ? "text" : "password";
    passwordField.type = passwordType;
  });

  // Cập nhật mật khẩu mới
  document.getElementById("update-password").addEventListener("click", () => {
    const newPassword = document.getElementById("new-password").value;
    const username = sessionStorage.getItem("username");

    if (newPassword) {
      const user = JSON.parse(localStorage.getItem(username));
      user.password = newPassword;
      localStorage.setItem(username, JSON.stringify(user));

      alert("Mật khẩu đã được cập nhật!");
      document.getElementById("profile-popup").style.display = "none"; // Đóng popup
    } else {
      alert("Vui lòng nhập mật khẩu mới.");
    }
  });

  // Đóng popup khi bấm icon X
  document.getElementById("close-popup").addEventListener("click", () => {
    document.getElementById("profile-popup").style.display = "none";
  });
} else {
  // Nếu chưa đăng nhập, ẩn mục user
  document.getElementById("user-link").style.display = "none";

  // Hiển thị các mục đăng nhập và đăng ký
  document.getElementById("login-link").style.display = "block";
  document.getElementById("register-link").style.display = "block";
}

// Toggle menu "User"
document.getElementById("user-link").addEventListener("click", (e) => {
  const userMenu = document.getElementById("user-menu");
  userMenu.style.display = userMenu.style.display === "block" ? "none" : "block";
});
