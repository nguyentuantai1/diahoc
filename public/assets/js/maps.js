// Tạo bản đồ và đặt vị trí trung tâm tại Việt Nam
const map = L.map("map").setView([14.0583, 108.2772], 6); // Tọa độ Việt Nam

// Thêm lớp bản đồ từ OpenStreetMap
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

// Dữ liệu bệnh viện với các thông tin bổ sung
const hospitals = [];

// Hàm để lấy dữ liệu bệnh viện từ API PHP và nạp vào const hospitals
function fetchHospitals() {
  fetch('http://localhost/DIAHOIC/api/get_hospitals.php')
    .then(response => response.json())  // Chuyển đổi phản hồi thành JSON
    .then(data => {
      // Cập nhật mảng hospitals với dữ liệu nhận được từ API
      hospitals.length = 0;  // Xóa mảng cũ để tránh trùng dữ liệu
      hospitals.push(...data.map(hospital => ({
        name: hospital.name,
        address: hospital.address,
        lat: hospital.latitude,
        lng: hospital.longitude,
        specialty: hospital.type,  // Giả sử "type" trong cơ sở dữ liệu tương ứng với specialty
        availableBeds: 50,  // Đây là giá trị giả lập, bạn có thể cập nhật nếu có thông tin giường trống
      })));

      console.log(hospitals); // In ra mảng dữ liệu bệnh viện sau khi đã nạp

      // Lặp qua danh sách bệnh viện để thêm marker vào bản đồ
      const markers = [];
      hospitals.forEach((hospital) => {
        const marker = L.marker([hospital.lat, hospital.lng]).addTo(map);
        marker.bindPopup(`
          <b>${hospital.name}</b><br>
          ${hospital.address}<br>
          <strong>Khoa bệnh:</strong> ${hospital.specialty}<br>
          <strong>Số giường trống:</strong> ${hospital.availableBeds}
        `);
        markers.push({ name: hospital.name, marker });
      });
      console.log(markers); // In ra mảng markers
    })
    .catch(error => console.error('Error fetching data:', error));
}

// Gọi hàm fetchHospitals để lấy dữ liệu khi trang được tải
fetchHospitals();


// Tìm kiếm bệnh viện
document.getElementById("search-button").addEventListener("click", searchHospital);
document.getElementById("search-hospital").addEventListener("keydown", function (e) {
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

searchInput.addEventListener("input", function () {
  if (searchInput.value.length > 0) {
    clearSearch.style.display = "block"; // Hiển thị icon x
  } else {
    clearSearch.style.display = "none"; // Ẩn icon x
  }
});

// Xóa nội dung khi người dùng nhấn vào icon "x"
clearSearch.addEventListener("click", function () {
  searchInput.value = ""; // Xóa nội dung trong input
  clearSearch.style.display = "none"; // Ẩn icon x
});
