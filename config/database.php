<?php
class Database {
    public static function getConnection() {
        $env = parse_ini_file(__DIR__ . '/../.env');
        
        $host = $env['DB_HOST'];
        $db   = $env['DB_NAME'];
        $user = $env['DB_USER'];
        $pass = $env['DB_PASS'];

        try {
            $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            die("Database Connection Failed.");
        }
    }
}
?>