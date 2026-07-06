<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    
    private $userModel;

    public function __construct() {
        $db = Database::getConnection();
        $this->userModel = new UserModel($db);
    }

    public function handleRequest() {
        // CSRF Token Generate
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Action check karna (URL param se, jaise: index.php?action=login)
        $action = isset($_GET['action']) ? $_GET['action'] : 'register';

        switch ($action) {
            case 'login':
                $this->login();
                break;
            case 'dashboard':
                $this->dashboard();
                break;
            case 'logout':
                $this->logout();
                break;
            case 'register':
            default:
                $this->register();
                break;
        }
    }

    // --- REGISTRATION LOGIC ---
    private function register() {
        $message = "";
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // CSRF Check
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $message = "<div class='msg error'>Security Alert: CSRF Failed!</div>";
            } else {
                $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
                $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];

                if (empty($name) || empty($email) || empty($password)) {
                    $message = "<div class='msg error'>All fields are required!</div>";
                } elseif (strlen($password) < 6) {
                    $message = "<div class='msg error'>Password must be at least 6 characters long.</div>";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $result = $this->userModel->registerUser($name, $email, $hashedPassword);

                    if ($result === true) {
                        $message = "<div class='msg success'>Registration Successful! <a href='index.php?action=login'>Login here</a></div>";
                    } elseif ($result === "email_exists") {
                        $message = "<div class='msg error'>Email already registered!</div>";
                    } else {
                        $message = "<div class='msg error'>Database Error. Try again.</div>";
                    }
                }
            }
        }
        require_once __DIR__ . '/../views/register.php';
    }

    // --- LOGIN LOGIC ---
    private function login() {
        $message = "";
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // CSRF Check
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $message = "<div class='msg error'>Security Alert: CSRF Failed!</div>";
            } else {
                $email = trim($_POST['email']);
                $password = $_POST['password'];

                if (empty($email) || empty($password)) {
                    $message = "<div class='msg error'>Please enter email and password.</div>";
                } else {
                    // Database se user nikalna
                    $user = $this->userModel->getUserByEmail($email);

                    // password_verify check karega ke plain text database ke hashed password se match karta hai ya nahi
                    if ($user && password_verify($password, $user['password'])) {
                        // User authenticate ho gaya, session mein save karo!
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        
                        // Dashboard par bhej do
                        header("Location: index.php?action=dashboard");
                        exit();
                    } else {
                        $message = "<div class='msg error'>Invalid Email or Password!</div>";
                    }
                }
            }
        }
        require_once __DIR__ . '/../views/login.php';
    }

    // --- DASHBOARD LOGIC ---
    private function dashboard() {
        // Security: Check agar user login nahi hai, toh wapis login page par bhej do
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        require_once __DIR__ . '/../views/dashboard.php';
    }

    // --- LOGOUT LOGIC ---
    private function logout() {
        // Session destroy karo aur memory saaf karo
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
}
?>