<?php
$mysqli = new mysqli("localhost", "root", "", "hospital_management");

// Check connection
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  exit();
}

//tat ca trang nao co admin vut vao
session_start();

if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
  $cus = mysqli_fetch_assoc(mysqli_query($mysqli, "select * from users where username = '" . $_SESSION["username"] . "'"));
  if ($cus["role"] == "admin") {
  } else {
    header("Location:http://localhost/DIAHOIC/view/login.php");
  }
} else {
  header("Location:http://localhost/DIAHOIC/view/login.php");
}

$users = mysqli_query($mysqli, "select * from users");
$hospitals = mysqli_query($mysqli, "select * from hospitals");

$departments = mysqli_query($mysqli, "select * from departments ");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Lấy dữ liệu từ form
  $hospital_name = $_POST['hospital-name'];
  $address = $_POST['address'];
  $lat = $_POST['lat'];
  $lng = $_POST['lng'];
  $available_beds = $_POST['available-beds'];
  $selected_departments = isset($_POST['departments']) ? $_POST['departments'] : [];

  // Thêm bệnh viện vào cơ sở dữ liệu
  $stmt = $mysqli->prepare("INSERT INTO hospitals (name, address, latitude, longitude, available_beds) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("ssddi", $hospital_name, $address, $lat, $lng, $available_beds);
  if ($stmt->execute()) {
      // Lấy ID của bệnh viện vừa thêm vào
      $hospital_id = $stmt->insert_id;

      // Thêm thông tin các khoa vào bảng liên kết giữa bệnh viện và khoa
      foreach ($selected_departments as $department_id) {
          $stmt_department = $mysqli->prepare("INSERT INTO hospital_departments (hospital_id, department_id) VALUES (?, ?)");
          $stmt_department->bind_param("ii", $hospital_id, $department_id);
          $stmt_department->execute();
      }

      echo "Bệnh viện đã được thêm thành công!";
  } else {
      echo "Lỗi khi thêm bệnh viện.";
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản trị hệ thống</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="http://localhost/DIAHOIC/public/assets/css/admin.css" />
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>
  <!-- Header -->
  <header>
    <h1>Trang Quản Trị</h1>
    <nav>
      <ul>
        <li>
          <a href="http://localhost/DIAHOIC/view/home.php"><i class="ti-home"></i> Trang chủ</a>
        </li>
      </ul>
    </nav>
  </header>

  <!-- Main content -->
  <main>
    <!-- Quản lý tài khoản -->
    <section>
      <h2>Quản lý Tài khoản</h2>
      <table id="account-management">
        <thead>
          <tr>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Mật khẩu</th>
            <th>Phân quyền</th>
            <th>Hành động</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($users as $p) { ?>
            <tr>
              <td><?php echo $p['username']; ?></td>
              <td><?php echo $p['email']; ?></td>
              <td><?php echo $p['password']; ?></td>
              <td><?php echo $p['role']; ?></td>
              <td>
                <a class="btn btn-danger" href="update.php?id=<?php echo $p['id']; ?>">Update</a>
                <a class="btn btn-info" href="?id=<?php echo $p['id']; ?>">Delete</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>


      </table>
      <button id="add-account">Thêm tài khoản</button>
    </section>

    <!-- Quản lý thông tin bệnh viện -->
    <section>
      <h2>Quản lý Thông tin Bệnh viện</h2>
      <table id="hospital-management">
        <thead>
          <tr>
            <th>Tên bệnh viện</th>
            <th>Địa chỉ</th>
            <th>Vĩ độ</th>
            <th>Kinh độ</th>
            <th>Loại bệnh viện</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($hospitals as $p) { ?>
            <tr>
              <td><?php echo $p['name']; ?></td>
              <td><?php echo $p['address']; ?></td>
              <td><?php echo $p['latitude']; ?></td>
              <td><?php echo $p['longitude']; ?></td>
              <td><?php echo $p['type']; ?></td>

              <td>
                <a class="btn btn-danger" href="update.php?id=<?php echo $p['id']; ?>">Update</a>
                <a class="btn btn-info" href="?id=<?php echo $p['id']; ?>">Delete</a>
              </td>
            </tr>
          <?php } ?>

        </tbody>
      </table>
      <button id="add-hospital">Thêm bệnh viện</button>
    </section>
  </main>
  <!-- Popup Modal cho Tài khoản -->
  <div id="account-modal" class="modal">
    <div class="modal-content">
      <span class="close" id="close-account-modal"><i class="fas fa-times"></i></span>
      <h2 id="account-modal-title">Thêm mới Tài khoản</h2>
      <form id="account-form">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" required />
        <label for="email">Email:</label>
        <input type="email" id="email" required />
        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" required />
        <label for="role">Phân quyền:</label>
        <select id="role">
          <option value="Admin">Admin</option>
          <option value="Doctor">Doctor</option>
          <option value="User">User</option>
        </select>
        <button type="submit">Lưu</button>
      </form>
    </div>
  </div>
  <!-- Popup Modal cho Bệnh viện -->
  <div id="hospital-modal" class="modal">
    <div class="modal-content">
      <span class="close" id="close-hospital-modal"><i class="fas fa-times"></i></span>
      <h2 id="hospital-modal-title">Thêm mới Bệnh viện</h2>
      <form id="hospital-form">
        <label for="hospital-name">Tên bệnh viện:</label>
        <input type="text" id="hospital-name" required />
        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" required />
        <label for="lat">Vĩ độ:</label>
        <input type="text" id="lat" step="any" required />
        <label for="lng">Kinh độ:</label>
        <input type="text" id="lng" step="any" required />
        <label for="departments">Khoa khám bệnh:</label>
        <div id="departments">
          <?php foreach ($departments as $p) { ?>
            <label><input type="checkbox" value="<?php echo $p['department_id']; ?>" /> <?php echo $p['name']; ?></label>
          <?php } ?>
        </div>
        <label for="available-beds">Số giường bệnh còn trống:</label>
        <input type="number" id="available-beds" required />
        <button type="submit">Lưu</button>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>© 2024 - Hệ thống quản lý bệnh viện - Thành phố Hà Nội</p>
  </footer>

  <!-- Scripts -->
  <script src="http://localhost/DIAHOIC/public/assets/js/admin.js"></script>
</body>

</html>
<?php
$mysqli->close();
?>