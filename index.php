<?php
session_start(); 
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

require_once __DIR__ . '/controllers/UserController.php';

$app = new UserController();
$app->handleRequest();
?>