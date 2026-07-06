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
            if ($e->getCode() == 23000) { // Duplicate email error code
                return "email_exists";
            }
            return false;
        }
    }
}
?>