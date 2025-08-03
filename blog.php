<?php
require_once 'config/database.php';
$db = new Database();
$pdo = $db->connect();

$slug = $_GET['slug'] ?? null;

if ($slug) {
    // ✅ Show SINGLE blog
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 'published'");
    $stmt->execute([$slug]);
    $blog = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BLOG - ISKCONA AGRI TECH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <?php if ($slug && $blog): ?>
            <!-- ✅ Full Blog View -->
            <h1><?= htmlspecialchars($blog['title']) ?></h1>
            <p class="text-muted">🕒 <?= date('F d, Y', strtotime($blog['created_at'])) ?> | 🏷️
                <?= htmlspecialchars($blog['category']) ?></p>
            <img src="admin/uploads/<?= htmlspecialchars($blog['image']) ?>" class="img-fluid mb-4">
            <div class="mb-5">
                <?= $blog['content'] ?>
            </div>
            <a href="blog.php" class="btn btn-outline-secondary">⬅ Back to Blogs</a>

        <?php else: ?>
            <!-- ✅ Blog Cards View -->
            <h2 class="mb-4 text-center">📰 Latest Blogs</h2>
            <div class="row">
                <?php
                $allBlogs = $pdo->query("SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC")->fetchAll();
                foreach ($allBlogs as $b):
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="admin/uploads/<?= htmlspecialchars($b['image']) ?>" class="card-img-top"
                                alt="<?= $b['title'] ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($b['title']) ?></h5>
                                <p class="card-text text-muted small"><?= ucfirst($b['category']) ?> |
                                    <?= date('M d, Y', strtotime($b['created_at'])) ?></p>
                                <p class="card-text"><?= mb_strimwidth(strip_tags($b['content']), 0, 100, '...') ?></p>
                                <div class="mt-auto">
                                    <a href="blog.php?slug=<?= urlencode($b['slug']) ?>"
                                        class="btn btn-sm btn-outline-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>