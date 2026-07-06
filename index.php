<?php
// Initialize the Controller and route the request
require_once __DIR__ . '/controllers/UserController.php';

$app = new UserController();
$app->handleRequest();
?>