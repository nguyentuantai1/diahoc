<?php
class Review {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy đánh giá theo ID bệnh viện
    public function getReviewsByHospitalId($hospitalId) {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE hospital_id = :hospital_id ORDER BY created_at DESC");
        $stmt->execute(['hospital_id' => $hospitalId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tạo đánh giá mới
    public function createReview($hospitalId, $userId, $rating, $comment) {
        $stmt = $this->pdo->prepare("INSERT INTO reviews (hospital_id, user_id, rating, comment) VALUES (:hospital_id, :user_id, :rating, :comment)");
        $stmt->execute([
            'hospital_id' => $hospitalId,
            'user_id' => $userId,
            'rating' => $rating,
            'comment' => $comment
        ]);
        return $this->pdo->lastInsertId();
    }

    // Lấy trung bình xếp hạng của bệnh viện
    public function getAverageRating($hospitalId) {
        $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE hospital_id = :hospital_id");
        $stmt->execute(['hospital_id' => $hospitalId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'];
    }
}
