<?php
class Department {
    private $pdo;

    // Constructor nhận đối tượng PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy tất cả các phòng ban
    public function getAllDepartments() {
        $stmt = $this->pdo->query("SELECT * FROM departments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy phòng ban theo ID
    public function getDepartmentById($departmentId) {
        $stmt = $this->pdo->prepare("SELECT * FROM departments WHERE department_id = :department_id");
        $stmt->execute(['department_id' => $departmentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm phòng ban mới
    public function createDepartment($name, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO departments (name, description) VALUES (:name, :description)");
        $stmt->execute([
            'name' => $name,
            'description' => $description
        ]);
        return $this->pdo->lastInsertId();
    }

    // Cập nhật thông tin phòng ban
    public function updateDepartment($departmentId, $name, $description) {
        $stmt = $this->pdo->prepare("UPDATE departments SET name = :name, description = :description WHERE department_id = :department_id");
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'department_id' => $departmentId
        ]);
    }

    // Xóa phòng ban
    public function deleteDepartment($departmentId) {
        $stmt = $this->pdo->prepare("DELETE FROM departments WHERE department_id = :department_id");
        $stmt->execute(['department_id' => $departmentId]);
    }
}
