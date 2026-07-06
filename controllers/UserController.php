<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    
    public function handleRequest() {
        session_start();
        $message = "";

        // CSRF Fix Step 1 & 2: Generate a random, cryptographically secure string and save in $_SESSION
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Coordinate Data: Read incoming POST request
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $message = $this->processRegistration();
        }

        // Decide which View to load
        require_once __DIR__ . '/../views/register.php';
    }

    private function processRegistration() {
        // CSRF Fix Step 4: Verify if $_POST matches $_SESSION. Reject immediately if they don't match.
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            return "<div class='msg error'>Security Alert: CSRF Token missing or invalid!</div>";
        }

        // Sanitize browser data
        $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            return "<div class='msg error'>All fields are required!</div>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "<div class='msg error'>Invalid email format!</div>";
        } elseif (strlen($password) < 6) {
            return "<div class='msg error'>Password must be at least 6 characters long.</div>";
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Ask the Data Layer (Model) to perform database updates
        $db = Database::getConnection();
        $userModel = new UserModel($db);
        $result = $userModel->registerUser($name, $email, $hashedPassword);

        if ($result === true) {
            return "<div class='msg success'>Registration Successful!</div>";
        } elseif ($result === "email_exists") {
            return "<div class='msg error'>Email is already registered!</div>";
        } else {
            return "<div class='msg error'>Database Error. Try again.</div>";
        }
    }
}
?>