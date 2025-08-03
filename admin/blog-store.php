<?php

session_start();

// Show all PHP errors (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';

$db = new Database();
$pdo = $db->connect(); // ✅ this is what gives you the $pdo object



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    $category = $_POST['category'];
    $tags = trim($_POST['tags']);
    $content = $_POST['content']; // comes from CKEditor
    $status = $_POST['status'];

    // Handle image upload
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetDir = 'uploads/';
        if (!is_dir($targetDir))
            mkdir($targetDir);
        $targetPath = $targetDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    // Insert into database
    try {
        $stmt = $pdo->prepare("INSERT INTO blogs (title, slug, category, tags, image, content, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $title,
            $slug,
            $category,
            $tags,
            $imageName,
            $content,
            $status
        ]);

        $_SESSION['success'] = "✅ Blog created successfully!";
        header("Location: blog-list.php");
        exit;

    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
} else {
    header('Location: blog-create.php');
    exit;
}
?>