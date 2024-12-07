<?php
class Hospital {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy danh sách tất cả bệnh viện
    public function getAllHospitals() {
        $stmt = $this->pdo->query("SELECT * FROM hospitals");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin bệnh viện theo ID
    public function getHospitalById($hospitalId) {
        $stmt = $this->pdo->prepare("SELECT * FROM hospitals WHERE hospital_id = :hospital_id");
        $stmt->execute(['hospital_id' => $hospitalId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm bệnh viện mới
    public function createHospital($name, $latitude, $longitude, $type, $phone, $address) {
        $stmt = $this->pdo->prepare("INSERT INTO hospitals (name, latitude, longitude, type, phone, address) VALUES (:name, :latitude, :longitude, :type, :phone, :address)");
        $stmt->execute([
            'name' => $name,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'type' => $type,
            'phone' => $phone,
            'address' => $address
        ]);
        return $this->pdo->lastInsertId();
    }
}
