// Danh sách bệnh viện từ maps.js
const hospitals = [
  { name: "Bệnh viện Đa Khoa Phương Đông", image: "https://intechservice.vn/wp-content/uploads/2024/05/benh-vien-da-khoa-phuong-dong.png" },
  { name: "Bệnh viện Bạch Mai", image: "https://congchungnguyenhue.com/Uploaded/Images/Original/2023/11/06/bvbachmai_0611113832.jpg" },
  { name: "Bệnh viện E", image: "https://www.bowtie.com.vn/blog/wp-content/uploads/2023/02/benh-vien-e.jpg" },
  { name: "Bệnh viện Việt Đức", image: "https://lh3.googleusercontent.com/p/AF1QipNTi3_pUG7BT3OwbVW12fAfyukR06UUYhU0fZR_=s1360-w1360-h1020" },
  { name: "Bệnh viện Phụ sản Hà Nội", image: "https://lh3.googleusercontent.com/p/AF1QipOO7mQcZXRA8i235rUCGaDX_mePKd_CFcdSSnqk=s1360-w1360-h1020" },
  { name: "Bệnh viện Nhi Trung Ương", image: "https://lh3.googleusercontent.com/p/AF1QipOtAE-viakRH_Ni5OF5P8Jl2myC671OOL6_KTIP=s1360-w1360-h1020" },
  { name: "Bệnh viện Đa Khoa Xanh Pôn", image: "https://congchungnguyenhue.com/Uploaded/Images/Original/2023/11/09/bvxanhpon_0911092243.jpg" },
  { name: "Bệnh viện Thanh Nhàn", image: "https://imagev3.vietnamplus.vn/w1000/Uploaded/2024/qfsqy/2021_05_12/ttxvn_Banh_vien_Thanh_nhan.jpg.webp" },
  { name: "Bệnh viện Tim Hà Nội", image: "https://lh3.googleusercontent.com/p/AF1QipP3GovkR-4qO8qCLE60J1OGxzS8UUU3gvxqpV7U=s1360-w1360-h1020" },
  { name: "Bệnh viện 19 - 8 Bộ Công An", image: "https://benhvien198.net/Images/images/IMG_5527.jpg" },
];

// Hiển thị danh sách bệnh viện
const hospitalSelect = document.getElementById("hospital-select");
const hospitalImage = document.getElementById("hospital-image");

hospitals.forEach((hospital) => {
  const option = document.createElement("option");
  option.value = hospital.name;
  option.textContent = hospital.name;
  hospitalSelect.appendChild(option);
});

// Hiển thị hình ảnh bệnh viện khi chọn
hospitalSelect.addEventListener("change", () => {
  const selectedHospital = hospitalSelect.value;
  const hospital = hospitals.find((h) => h.name === selectedHospital);

  if (hospital) {
    hospitalImage.src = hospital.image;
    hospitalImage.style.display = "block";
  } else {
    hospitalImage.style.display = "none";
  }
});

// Lưu và hiển thị đánh giá
let currentRating = 0;

document.querySelectorAll(".star").forEach((star) => {
  star.addEventListener("click", () => {
    currentRating = star.getAttribute("data-star");
    document.querySelectorAll(".star").forEach((s) => s.classList.remove("active"));
    for (let i = 0; i < currentRating; i++) {
      document.querySelectorAll(".star")[i].classList.add("active");
    }
  });
});

document.getElementById("submit-review").addEventListener("click", () => {
  const selectedHospital = hospitalSelect.value;
  const comment = document.getElementById("review-comment").value;
  const username = sessionStorage.getItem("username");

  if (!selectedHospital || !currentRating || !comment || !username) {
    alert("Vui lòng đăng nhập hoặc thực hiện đủ chức năng!");
    return;
  }

  const review = {
    hospital: selectedHospital,
    rating: currentRating,
    comment,
    username,
    time: new Date().toLocaleString(),
    replies: [],
    likes: 0,
  };

  const reviews = JSON.parse(localStorage.getItem("reviews")) || [];
  reviews.push(review);
  localStorage.setItem("reviews", JSON.stringify(reviews));

  displayReviews();
});

// Cập nhật hàm displayReviews để xử lý câu trả lời và thả cảm xúc
function displayReviews() {
  const reviews = JSON.parse(localStorage.getItem("reviews")) || [];
  const reviewsList = document.getElementById("reviews-list");
  reviewsList.innerHTML = "";

  reviews.forEach((review, index) => {
    const reviewItem = document.createElement("div");
    reviewItem.classList.add("review-item");
    reviewItem.innerHTML = `
      <h4>${review.username} đã bình luận về ${review.hospital}</h4>
      <p>Đánh giá: ${"★".repeat(review.rating)}${"☆".repeat(5 - review.rating)}</p>
      <p>${review.comment}</p>
      <small>Đánh giá vào: ${review.time}</small>
      <button class="like-btn" data-index="${index}">❤️ Thích (${review.likes})</button>
      ${sessionStorage.getItem("isLoggedIn") === "true" ? 
      `<button class="reply-btn" data-index="${index}">Trả lời</button>
       <button class="delete-btn" data-index="${index}">Xóa</button>` : ''
      }
      <div class="replies">
        ${review.replies.map((reply, replyIndex) => `
          <div class="reply">
            <p><strong>${reply.username}</strong>: ${reply.comment} <small>vào ${reply.time}</small></p>
            <button class="like-reply-btn" data-review-index="${index}" data-reply-index="${replyIndex}">❤️ Thích (${reply.likes})</button>
            ${sessionStorage.getItem("isLoggedIn") === "true" ? 
              `<button class="reply-to-reply-btn" data-review-index="${index}" data-reply-index="${replyIndex}">Trả lời</button>` : ''
            }
            <div class="replies-to-reply">
              ${reply.replies.map(replyToReply => `
                <div class="reply-to-reply">
                  <p><strong>${replyToReply.username}</strong>: ${replyToReply.comment} <small>vào ${replyToReply.time}</small></p>
                </div>`).join('')}
            </div>
            <div class="reply-to-reply-form" data-review-index="${index}" data-reply-index="${replyIndex}" style="display:none">
              <textarea class="reply-to-reply-comment" placeholder="Nhập câu trả lời..."></textarea>
              <button class="submit-reply-to-reply">Gửi trả lời</button>
            </div>
          </div>`).join('')}
      </div>
      <div class="reply-form" data-index="${index}" style="display:none">
        <textarea class="reply-comment" placeholder="Nhập câu trả lời..."></textarea>
        <button class="submit-reply">Gửi trả lời</button>
      </div>
    `;
    reviewsList.appendChild(reviewItem);
  });

  // Thêm sự kiện thả cảm xúc cho các câu trả lời
  document.querySelectorAll(".like-reply-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";  // Kiểm tra trạng thái đăng nhập
      const reviewIndex = btn.getAttribute("data-review-index");
      const replyIndex = btn.getAttribute("data-reply-index");

      if (!isLoggedIn) {
        alert("Vui lòng đăng nhập để thả tim bài viết!");
        return;
      }

      const reviews = JSON.parse(localStorage.getItem("reviews"));
      reviews[reviewIndex].replies[replyIndex].likes++;
      localStorage.setItem("reviews", JSON.stringify(reviews));
      displayReviews();
    });
  });

  // Thêm sự kiện trả lời câu trả lời
  document.querySelectorAll(".reply-to-reply-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const reviewIndex = btn.getAttribute("data-review-index");
      const replyIndex = btn.getAttribute("data-reply-index");
      const replyForm = document.querySelector(`.reply-to-reply-form[data-review-index='${reviewIndex}'][data-reply-index='${replyIndex}']`);
      replyForm.style.display = "block";
    });
  });

  // Xử lý việc gửi câu trả lời cho câu trả lời
  document.querySelectorAll(".submit-reply-to-reply").forEach((btn) => {
    btn.addEventListener("click", () => {
      const reviewIndex = btn.closest(".reply-to-reply-form").getAttribute("data-review-index");
      const replyIndex = btn.closest(".reply-to-reply-form").getAttribute("data-reply-index");
      const replyText = btn.previousElementSibling.value;
      const username = sessionStorage.getItem("username");

      if (!replyText || !username) {
        alert("Vui lòng nhập đầy đủ thông tin.");
        return;
      }

      const reviews = JSON.parse(localStorage.getItem("reviews"));
      reviews[reviewIndex].replies[replyIndex].replies.push({
        username,
        comment: replyText,
        time: new Date().toLocaleString(),
      });
      localStorage.setItem("reviews", JSON.stringify(reviews));
      displayReviews();
    });
  });

  // Thêm sự kiện trả lời cho bình luận gốc
  document.querySelectorAll(".reply-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const reviewIndex = btn.getAttribute("data-index");
      const replyForm = document.querySelector(`.reply-form[data-index='${reviewIndex}']`);
      replyForm.style.display = "block";
    });
  });

  // Xử lý việc gửi câu trả lời cho bình luận gốc
  document.querySelectorAll(".submit-reply").forEach((btn) => {
    btn.addEventListener("click", () => {
      const reviewIndex = btn.closest(".reply-form").getAttribute("data-index");
      const replyText = btn.previousElementSibling.value;
      const username = sessionStorage.getItem("username");

      if (!replyText || !username) {
        alert("Vui lòng nhập đầy đủ thông tin.");
        return;
      }

      const reviews = JSON.parse(localStorage.getItem("reviews"));
      reviews[reviewIndex].replies.push({
        username,
        comment: replyText,
        time: new Date().toLocaleString(),
        replies: [],
        likes: 0,
      });
      localStorage.setItem("reviews", JSON.stringify(reviews));
      displayReviews();
    });
  });

  // Thêm sự kiện thả cảm xúc cho bình luận
  document.querySelectorAll(".like-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";  // Kiểm tra trạng thái đăng nhập
      const reviewIndex = btn.getAttribute("data-index");

      if (!isLoggedIn) {
        alert("Vui lòng đăng nhập để thả tim bài viết!");
        return;
      }

      const reviews = JSON.parse(localStorage.getItem("reviews"));
      reviews[reviewIndex].likes++;
      localStorage.setItem("reviews", JSON.stringify(reviews));
      displayReviews();
    });
  });

  // Xóa đánh giá
  document.querySelectorAll(".delete-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const reviewIndex = btn.getAttribute("data-index");
      const reviews = JSON.parse(localStorage.getItem("reviews"));
      reviews.splice(reviewIndex, 1);
      localStorage.setItem("reviews", JSON.stringify(reviews));
      displayReviews();
    });
  });
}

// Hiển thị đánh giá khi trang được tải lại
displayReviews();
