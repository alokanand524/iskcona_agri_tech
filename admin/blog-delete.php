<?php
require_once '../config/database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ DB connection
$db = new Database();
$pdo = $db->connect();

// ✅ Get blog ID from URL
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
        $stmt->execute([$id]);

        session_start();
        $_SESSION['success'] = "🗑️ Blog deleted successfully!";
    } catch (PDOException $e) {
        die("❌ Delete Error: " . $e->getMessage());
    }
}

header('Location: blog-list.php');
exit;
