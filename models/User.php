<?php


session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}



class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy thông tin người dùng theo ID
    public function getUserById($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    

    // Lấy thông tin người dùng theo username
    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo người dùng mới
    public function createUser($name, $username, $password, $email) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (name, username, password, email) VALUES (:name, :username, :password, :email)");
        $stmt->execute([
            'name' => $name,
            'username' => $username,
            'password' => $hashedPassword,
            'email' => $email
        ]);
        return $this->pdo->lastInsertId();
    }

    // Kiểm tra username hoặc email đã tồn tại
    public function isUsernameOrEmailExists($username, $email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

