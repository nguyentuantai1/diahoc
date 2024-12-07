<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đánh giá bệnh viện</title>
    <link rel="stylesheet" href="http://localhost/DIAHOIC/public/assets/css/reviews.css" />
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Đánh giá bệnh viện</h1>
        <nav>
            <ul>
                <li>
                    <?php
                    // Kiểm tra trạng thái đăng nhập
                    session_start();
                    if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) {
                        $homeLink = "http://localhost/DIAHOIC/main.php"; // Trang chủ đã đăng nhập
                    } else {
                        $homeLink = "http://localhost/DIAHOIC/main.php#reviews"; // Trang chủ chưa đăng nhập
                    }
                    ?>
                    <a href="<?= $homeLink ?>" id="back-home-link"><i class="ti-home"></i> Trang chủ</a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main content -->
    <main>
        <div id="review-section">
            <h2>Chọn bệnh viện để đánh giá</h2>
            <select id="hospital-select">
                <option value="">-- Chọn bệnh viện --</option>
                <!-- Đây là nơi bạn có thể thêm các tùy chọn động từ cơ sở dữ liệu -->
                <?php
                // Ví dụ: lấy danh sách bệnh viện từ cơ sở dữ liệu (giả sử kết nối đã được thiết lập)
                // $result = $db->query("SELECT id, name FROM hospitals");
                // while ($row = $result->fetch_assoc()) {
                //     echo "<option value='{$row['id']}'>{$row['name']}</option>";
                // }
                ?>
            </select>

            <!-- Hiển thị hình ảnh bệnh viện -->
            <img id="hospital-image" src="" alt="" style="display: none" />

            <div id="rating-form">
                <h3>Đánh giá dịch vụ</h3>
                <div id="star-rating">
                    <span data-star="1" class="star">&#9733;</span>
                    <span data-star="2" class="star">&#9733;</span>
                    <span data-star="3" class="star">&#9733;</span>
                    <span data-star="4" class="star">&#9733;</span>
                    <span data-star="5" class="star">&#9733;</span>
                </div>

                <textarea
                    id="review-comment"
                    placeholder="Nhập bình luận..."
                    rows="4"
                ></textarea>
                <button id="submit-review">Gửi đánh giá</button>
            </div>

            <h3>Đánh giá gần đây</h3>
            <div id="reviews-list">
                <!-- Đây là nơi bạn có thể hiển thị các đánh giá từ cơ sở dữ liệu -->
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2024 - Hệ thống quản lý bệnh viện - Thành phố Hà Nội</p>
    </footer>

    <script src="http://localhost/DIAHOIC/public/assets/js/reviews.js"></script>
    <script>
        // Xử lý hiển thị đánh giá hoặc các chức năng khác nếu cần
    </script>
</body>
</html>
