<?php
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
  echo '<script>
            localStorage.setItem("logged_in", "true");
            localStorage.setItem("username", "' . $_SESSION['username'] . '");
          </script>';
} else {
  echo '<script>
            localStorage.setItem("logged_in", "false");
          </script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hệ thống quản lý bệnh viện</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="http://localhost/DIAHOIC/public/assets/css/home.css" />

  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link
    rel="stylesheet"
    href="http://localhost/DIAHOIC/public/assets/icons/fontawesome-free-6.6.0-web/fontawesome-free-6.6.0-web/css/fontawesome.min.css" />
  <link
    rel="stylesheet"
    href="http://localhost/DIAHOIC/public/assets/icons/themify-icons-font/themify-icons/themify-icons.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>
</head>

<body>
  <!-- Header -->
  <header>
    <h1>Hệ thống quản lý bệnh viện</h1>
    <nav>
      <ul>
        <li>
          <a href="/DIAHOIC/main.php?page=home"><i class="ti-home"></i> Trang chủ</a>
        </li>

        <li>
          <a href="/DIAHOIC/main.php?page=admin" id="admin-link"><i class="ti-settings"></i> Quản lý</a>
        </li>

        <li>
          <a href="/DIAHOIC/main.php?page=review"><i class="ti-star"></i> Đánh giá</a>
        </li>

        <li>
          <div id="search-container">
            <input type="text" id="search-hospital" placeholder="Tìm bệnh viện..." />
            <span id="clear-search" class="ti-close" style="display: none"></span>
            <button id="search-button"><i class="ti-search"></i></button>
          </div>
        </li>

        <!-- Liên kết đăng nhập và đăng ký khi chưa đăng nhập -->
        <li class="auth-link" id="login-link-item" style="display: none;">
          <a href="/DIAHOIC/main.php?page=login" id="login-link"><i class="ti-lock"></i> Đăng nhập</a>
        </li>
        <li class="auth-link" id="register-link-item" style="display: none;">
          <a href="/DIAHOIC/main.php?page=register" id="register-link"><i class="ti-pencil-alt"></i> Đăng ký</a>
        </li>

        <!-- Liên kết người dùng khi đã đăng nhập -->
        <!-- <li class="user-link" id="user-link" style="display: none;">
            <a href="#"><i class="ti-user"></i> User</a>
            <ul id="user-menu" style="display: none;">
                <li><a href="#" id="view-profile">Xem thông tin tài khoản</a></li>
                <li><a href="#" id="logout">Đăng xuất</a></li>
            </ul>
        </li> -->
        <li class="user-link" id="user-link" style="display: block;">
          <a href="#"><i class="ti-user"></i> <span id="username-display">User</span></a>
          <ul id="user-menu" style="display: none;">
            <li><a href="#" id="view-profile">Xem thông tin tài khoản</a></li>
            <li><a href="http://localhost/DIAHOIC/view/logout.php" id="logout">Đăng xuất</a></li>
          </ul>
        </li>

      </ul>
    </nav>
  </header>


  <!-- Popup thông tin tài khoản -->
  <div id="profile-popup" class="popup">
    <div class="popup-content">
      <span id="close-popup" class="popup-close">&times;</span>
      <h3>Thông tin tài khoản</h3>
      <p><strong>Tên đăng nhập:</strong> <span id="profile-username"></span></p>
      <p>
        <strong>Mật khẩu:</strong>
        <input type="password" id="profile-password" disabled />
        <i id="toggle-password" class="ti-eye"></i>
      </p>
      <p><strong>Cập nhật mật khẩu mới:</strong><input type="password" id="new-password" placeholder="Nhập mật khẩu mới" /></p>
      <button id="update-password">Cập nhật mật khẩu</button>
    </div>
  </div>

  <!-- Main content -->
  <main>
    <div id="map"></div>
  </main>

  <!-- Footer -->
  <footer>
    <p>© 2024 - Hệ thống quản lý bệnh viện - Thành phố Hà Nội</p>
  </footer>

  <!-- Scripts -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="http://localhost/DIAHOIC/public/assets/js/home.js"></script>
  <!-- <script src="http://localhost/DIAHOIC/public/assets/js/maps.js"></script> -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Kiểm tra trạng thái đăng nhập
      const loggedIn = localStorage.getItem("logged_in") === "true";
      const username = localStorage.getItem("username");

      // Các phần tử cần hiển thị/ẩn
      const userLink = document.getElementById("user-link");
      const loginLinkItem = document.getElementById("login-link-item");
      const registerLinkItem = document.getElementById("register-link-item");

      // Hiển thị thông tin người dùng nếu đã đăng nhập
      if (loggedIn) {
        userLink.style.display = "block";
        userLink.querySelector("a").innerHTML = "<i class='ti-user'></i> " + username;
        loginLinkItem.style.display = "none";
        registerLinkItem.style.display = "none";
      } else {
        userLink.style.display = "none";
        loginLinkItem.style.display = "block";
        registerLinkItem.style.display = "block";
      }
    });

    // Tạo bản đồ và đặt vị trí trung tâm tại Việt Nam
    const map = L.map("map").setView([14.0583, 108.2772], 6); // Tọa độ Việt Nam

    // Thêm lớp bản đồ từ OpenStreetMap
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    // Dữ liệu bệnh viện với các thông tin bổ sung
    const hospitals = [];
    const markers = [];

    // Hàm để lấy dữ liệu bệnh viện từ API PHP và nạp vào const hospitals
    function fetchHospitals() {
      fetch('http://localhost/DIAHOIC/api/get_hospitals.php')
        .then(response => response.json()) // Chuyển đổi phản hồi thành JSON
        .then(data => {
          // Cập nhật mảng hospitals với dữ liệu nhận được từ API
          hospitals.length = 0; // Xóa mảng cũ để tránh trùng dữ liệu
          hospitals.push(...data.map(hospital => ({
            name: hospital.name,
            address: hospital.address,
            lat: hospital.latitude,
            lng: hospital.longitude,
            specialty: hospital.type, // Giả sử "type" trong cơ sở dữ liệu tương ứng với specialty
            availableBeds: 50, // Đây là giá trị giả lập, bạn có thể cập nhật nếu có thông tin giường trống
          })));

          console.log(hospitals); // In ra mảng dữ liệu bệnh viện sau khi đã nạp

          // Lặp qua danh sách bệnh viện để thêm marker vào bản đồ
          
          hospitals.forEach((hospital) => {
            const marker = L.marker([hospital.lat, hospital.lng]).addTo(map);
            marker.bindPopup(`
          <b>${hospital.name}</b><br>
          ${hospital.address}<br>
          <strong>Khoa bệnh:</strong> ${hospital.specialty}<br>
          <strong>Số giường trống:</strong> ${hospital.availableBeds}
        `);
            markers.push({
              name: hospital.name,
              marker
            });
          });
          console.log(markers); // In ra mảng markers
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    // Gọi hàm fetchHospitals để lấy dữ liệu khi trang được tải
    fetchHospitals();


    // Tìm kiếm bệnh viện
    document.getElementById("search-button").addEventListener("click", searchHospital);
    document.getElementById("search-hospital").addEventListener("keydown", function(e) {
      if (e.key === "Enter") {
        searchHospital();
      }
    });

    function searchHospital() {
      const searchTerm = document.getElementById("search-hospital").value.toLowerCase();
      const hospital = markers.find(h => h.name.toLowerCase().includes(searchTerm));

      if (hospital) {
        map.setView([hospitals.find(h => h.name === hospital.name).lat, hospitals.find(h => h.name === hospital.name).lng], 12);
        hospital.marker.openPopup();
      } else {
        alert("Bệnh viện không tìm thấy!");
      }
    }

    // Hiển thị hoặc ẩn icon "x" khi người dùng nhập vào ô tìm kiếm
    const searchInput = document.getElementById("search-hospital");
    const clearSearch = document.getElementById("clear-search");

    searchInput.addEventListener("input", function() {
      if (searchInput.value.length > 0) {
        clearSearch.style.display = "block"; // Hiển thị icon x
      } else {
        clearSearch.style.display = "none"; // Ẩn icon x
      }
    });

    // Xóa nội dung khi người dùng nhấn vào icon "x"
    clearSearch.addEventListener("click", function() {
      searchInput.value = ""; // Xóa nội dung trong input
      clearSearch.style.display = "none"; // Ẩn icon x
    });
  </script>




</body>

</html>