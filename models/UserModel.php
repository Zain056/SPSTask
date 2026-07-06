<?php
class UserModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function registerUser($name, $email, $hashedPassword) {
        try {
            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword
            ]);
            return true;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate email 
                return "email_exists";
            }
            return false;
        }
    }
    public function getUserByEmail($email) {
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>