<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ DB Connection
require_once '../config/database.php';
$db = new Database();
$pdo = $db->connect();

// ✅ Get Blog ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Blog ID is required.");
}
$id = $_GET['id'];

// ✅ Fetch Blog Data
$blogStmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
$blogStmt->execute([$id]);
$data = $blogStmt->fetch();

if (!$data) {
    die("❌ Blog not found.");
}

// ✅ Fetch Categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include "includes/header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <style>
        .blog-edit {
            margin-left: 14rem;
            width: 650px;
            padding-top:6rem
        }
    </style>
</head>

<body>
    <div class="container mt-0 blog-edit">
        <?php include "includes/sidebar.php"; ?>

        <h2>📝 Edit Blog</h2>

        <form action="blog-update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>"
                    required>

            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($data['slug']) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['name'] ?>" <?= $cat['name'] === $data['category'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tags</label>
                <input type="text" name="tags" class="form-control" value="<?= htmlspecialchars($data['tags']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <?php if (!empty($data['image'])): ?>
                    <img src="uploads/<?= $data['image'] ?>" style="max-width: 200px;">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Change Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control"
                    rows="8"><?= htmlspecialchars($data['content']) ?></textarea>
                <script>CKEDITOR.replace('content');</script>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" <?= $data['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $data['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>

            <button class="btn btn-primary w-100">💾 Update Blog</button>
        </form>
    </div>

    <!-- CKEditor Upgrade warning notification removal -->
    <script>
        CKEDITOR.replace('content'); 

        // Hide the insecure version warning
        CKEDITOR.on('instanceReady', function () {
            const interval = setInterval(() => {
                const warningBox = document.querySelector('.cke_notification_warning');
                if (warningBox) {
                    warningBox.remove(); // completely removes the warning box
                    clearInterval(interval);
                }
            }, 500);
        });
    </script>

</body>

</html>