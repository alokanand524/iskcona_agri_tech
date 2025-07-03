<?php
require_once __DIR__ . '/database.php';

session_start();

// Site configuration
define('SITE_URL', 'http://localhost/iskcona_agri_tech/');
define('ADMIN_URL', SITE_URL . 'admin/');
define('UPLOAD_PATH', 'uploads/');

// Database configuration
$database = new Database();
$db = $database->connect();

// Helper functions
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . ADMIN_URL . 'login.php');
        exit();
    }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function uploadImage($file, $targetDir = UPLOAD_PATH) {
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $fileName = uniqid() . '.' . $imageFileType;
    $targetFile = $targetDir . $fileName;
    
    // Check if image file is actual image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return false;
    }
    
    // Check file size (5MB max)
    if ($file["size"] > 5000000) {
        return false;
    }
    
    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $fileName;
    }
    
    return false;
}