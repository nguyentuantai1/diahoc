<?php
header('Content-Type: application/json');

// Kết nối với cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital_management"; // Tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn lấy dữ liệu bệnh viện
$sql = "SELECT * FROM hospitals";
$result = $conn->query($sql);

$hospitals = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $hospitals[] = $row;
    }
}

$conn->close();

// Trả về dữ liệu dưới dạng JSON
echo json_encode($hospitals);
?>

