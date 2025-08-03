<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Include database connection
require_once '../config/database.php';
$db = new Database();
$pdo = $db->connect(); // use $pdo instead of $conn

// ✅ Success flash message
$successMessage = '';
if (isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']);
}

// ✅ Get all blogs
try {
    $blogs = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll();
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

include "includes/header.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Blog List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .blog-list {
            margin-top: 0rem;
            margin-left: 14rem;
            width: 70%;
            padding-top:6rem
        }

        @media screen and (max-width: 768px) {

            .blog-list {
                margin-top: 1rem;
                margin-left: 0rem;
                margin-right: 1rem;
            }
        }
    </style>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="container blog-list">

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <?= $successMessage ?>
            </div>
        <?php endif; ?>

        <h2>🗂️ All Blogs</h2>
        <a href="blog-create.php" class="btn btn-success mb-3">➕ Create New Blog</a>

        <table class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blogs as $blog): ?>
                    <tr>
                        <td><?= htmlspecialchars($blog['title']) ?></td>
                        <td><?= htmlspecialchars($blog['category']) ?></td>
                        <td>
                            <span class="badge bg-<?= $blog['status'] === 'published' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($blog['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($blog['created_at'])) ?></td>
                        <td>
                            <a href="blog-edit.php?id=<?= $blog['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                            <a href="blog-delete.php?id=<?= $blog['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure to delete?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>