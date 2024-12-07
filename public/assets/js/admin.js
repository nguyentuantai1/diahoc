// Lấy các phần tử modal
const accountModal = document.getElementById("account-modal");
const hospitalModal = document.getElementById("hospital-modal");
const closeAccountModal = document.getElementById("close-account-modal");
const closeHospitalModal = document.getElementById("close-hospital-modal");
const addAccountButton = document.getElementById("add-account");
const addHospitalButton = document.getElementById("add-hospital");

// Các biến lưu trữ chỉ mục của tài khoản và bệnh viện đang được chỉnh sửa
let editingAccountIndex = null;
let editingHospitalIndex = null;

// Mở modal Thêm tài khoản
addAccountButton.addEventListener("click", () => {
  editingAccountIndex = null;
  document.getElementById("account-modal-title").innerText = "Thêm mới Tài khoản";
  accountModal.style.display = "flex";
});

// Mở modal Thêm bệnh viện
addHospitalButton.addEventListener("click", () => {
  editingHospitalIndex = null;
  document.getElementById("hospital-modal-title").innerText = "Thêm mới Bệnh viện";
  hospitalModal.style.display = "flex";
});

// Đóng modal Tài khoản
closeAccountModal.addEventListener("click", () => {
  accountModal.style.display = "none";
});

// Đóng modal Bệnh viện
closeHospitalModal.addEventListener("click", () => {
  hospitalModal.style.display = "none";
});

// Ràng buộc nhập số dương cho vĩ độ và kinh độ, cho phép nhập dấu . và ,
document.getElementById("lat").addEventListener("input", (event) => {
  let value = event.target.value;
  value = value.replace(/[^0-9.,]/g, "");
  value = value.replace(/([.,])\1+/g, '$1');
  event.target.value = value;
});

document.getElementById("lng").addEventListener("input", (event) => {
  let value = event.target.value;
  value = value.replace(/[^0-9.,]/g, "");
  value = value.replace(/([.,])\1+/g, '$1');
  event.target.value = value;
});

// Chặn nhập số âm, kí tự đặc biệt ở mục Số giường bệnh còn trống, chỉ cho phép nhập số nguyên dương
document.getElementById("available-beds").addEventListener("input", (event) => {
  let value = event.target.value;
  value = value.replace(/[^0-9]/g, ""); // Loại bỏ tất cả ký tự không phải số
  event.target.value = value;
});

// // Lưu tài khoản vào localStorage
// document.getElementById("account-form").addEventListener("submit", (event) => {
//   event.preventDefault();
//   const username = document.getElementById("username").value;
//   const email = document.getElementById("email").value;
//   const password = document.getElementById("password").value;
//   const role = document.getElementById("role").value;

//   let accounts = JSON.parse(localStorage.getItem("accounts")) || [];
//   if (editingAccountIndex !== null) {
//     accounts[editingAccountIndex] = { username, email, password, role };
//   } else {
//     accounts.push({ username, email, password, role });
//   }
//   localStorage.setItem("accounts", JSON.stringify(accounts));

//   // Load lại tài khoản và làm mới giao diện
//   loadAccounts();

//   // Làm sạch các trường nhập liệu và đóng modal
//   document.getElementById("account-form").reset();
//   accountModal.style.display = "none";
// });

// Lưu bệnh viện vào localStorage
// document.getElementById("hospital-form").addEventListener("submit", (event) => {
//   event.preventDefault();
//   const hospitalName = document.getElementById("hospital-name").value;
//   const address = document.getElementById("address").value;
//   const lat = document.getElementById("lat").value;
//   const lng = document.getElementById("lng").value;

//   // Lấy danh sách các khoa được chọn
//   const departments = Array.from(document.querySelectorAll("#departments input:checked"))
//     .map(checkbox => checkbox.value)
//     .join(", ");
  
//   const availableBeds = document.getElementById("available-beds").value;

//   let hospitals = JSON.parse(localStorage.getItem("hospitals")) || [];
//   if (editingHospitalIndex !== null) {
//     hospitals[editingHospitalIndex] = { hospitalName, address, lat, lng, departments, availableBeds };
//   } else {
//     hospitals.push({ hospitalName, address, lat, lng, departments, availableBeds });
//   }
//   localStorage.setItem("hospitals", JSON.stringify(hospitals));

  // Load lại bệnh viện và làm mới giao diện
//   loadHospitals();

//   // Làm sạch các trường nhập liệu và đóng modal
//   document.getElementById("hospital-form").reset();
//   hospitalModal.style.display = "none";
// });

// // Tải tài khoản từ localStorage
// function loadAccounts() {
//   const accounts = JSON.parse(localStorage.getItem("accounts")) || [];
//   const accountTableBody = document.getElementById("account-management").getElementsByTagName("tbody")[0];
//   accountTableBody.innerHTML = "";
//   accounts.forEach((account, index) => {
//     const row = accountTableBody.insertRow();
//     row.innerHTML =
//       `<td>${account.username}</td>
//       <td>${account.email}</td>
//       <td>${account.password}</td>
//       <td>${account.role}</td>
//       <td>
//         <button class="edit-account" data-index="${index}">Sửa</button>
//         <button class="delete-account" data-index="${index}">Xóa</button>
//       </td>`;
//   });

//   // Thêm chức năng sửa tài khoản
//   document.querySelectorAll(".edit-account").forEach(button => {
//     button.addEventListener("click", (event) => {
//       editingAccountIndex = event.target.getAttribute("data-index");
//       const account = JSON.parse(localStorage.getItem("accounts"))[editingAccountIndex];
//       document.getElementById("username").value = account.username;
//       document.getElementById("email").value = account.email;
//       document.getElementById("password").value = account.password;
//       document.getElementById("role").value = account.role;
//       document.getElementById("account-modal-title").innerText = "Cập nhật tài khoản";
//       accountModal.style.display = "flex";
//     });
//   });

//   // Thêm chức năng xóa tài khoản
//   document.querySelectorAll(".delete-account").forEach(button => {
//     button.addEventListener("click", (event) => {
//       const index = event.target.getAttribute("data-index");
//       deleteAccount(index);
//     });
//   });
// }

// // Tải bệnh viện từ localStorage
// function loadHospitals() {
//   const hospitals = JSON.parse(localStorage.getItem("hospitals")) || [];
//   const hospitalTableBody = document.getElementById("hospital-management").getElementsByTagName("tbody")[0];
//   hospitalTableBody.innerHTML = "";
//   hospitals.forEach((hospital, index) => {
//     const row = hospitalTableBody.insertRow();
//     row.innerHTML =
//       `<td>${hospital.hospitalName}</td>
//       <td>${hospital.address}</td>
//       <td>${hospital.lat}</td>
//       <td>${hospital.lng}</td>
//       <td>${hospital.departments}</td>
//       <td>${hospital.availableBeds}</td>
//       <td>
//         <button class="edit-hospital" data-index="${index}">Sửa</button>
//         <button class="delete-hospital" data-index="${index}">Xóa</button>
//       </td>`;
//   });

//   // Thêm chức năng sửa bệnh viện
//   document.querySelectorAll(".edit-hospital").forEach(button => {
//     button.addEventListener("click", (event) => {
//       editingHospitalIndex = event.target.getAttribute("data-index");
//       const hospital = JSON.parse(localStorage.getItem("hospitals"))[editingHospitalIndex];
//       document.getElementById("hospital-name").value = hospital.hospitalName;
//       document.getElementById("address").value = hospital.address;
//       document.getElementById("lat").value = hospital.lat;
//       document.getElementById("lng").value = hospital.lng;

//       // Cập nhật các khoa đã chọn trong form
//       const departmentArray = hospital.departments.split(", ");
//       document.querySelectorAll("#departments input").forEach(checkbox => {
//         checkbox.checked = departmentArray.includes(checkbox.value);
//       });

//       document.getElementById("available-beds").value = hospital.availableBeds;
//       document.getElementById("hospital-modal-title").innerText = "Cập nhật bệnh viện";
//       hospitalModal.style.display = "flex";
//     });
//   });

//   // Thêm chức năng xóa bệnh viện
//   document.querySelectorAll(".delete-hospital").forEach(button => {
//     button.addEventListener("click", (event) => {
//       const index = event.target.getAttribute("data-index");
//       deleteHospital(index);
//     });
//   });
// }

// // Xóa tài khoản
// function deleteAccount(index) {
//   let accounts = JSON.parse(localStorage.getItem("accounts")) || [];
//   accounts.splice(index, 1);
//   localStorage.setItem("accounts", JSON.stringify(accounts));
//   loadAccounts();
// }

// // Xóa bệnh viện
// function deleteHospital(index) {
//   let hospitals = JSON.parse(localStorage.getItem("hospitals")) || [];
//   hospitals.splice(index, 1);
//   localStorage.setItem("hospitals", JSON.stringify(hospitals));
//   loadHospitals();
// }

// // Tải tài khoản và bệnh viện khi trang được tải
// loadAccounts();
// loadHospitals();
