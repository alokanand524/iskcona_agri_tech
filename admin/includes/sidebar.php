<?php
// Ensure $db or $conn is initialized before using this
require_once '../config/database.php';
$db = new Database();
$conn = $db->connect();

$currentPage = basename($_SERVER['PHP_SELF']);

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
$stmt->execute();
$unreadCount = $stmt->fetch()['count'] ?? 0;
?>
<div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'pages.php' ? 'active' : ''; ?>" href="pages.php">
                    <i class="fas fa-file-alt me-2"></i>Pages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'products.php' ? 'active' : ''; ?>" href="products.php">
                    <i class="fas fa-seedling me-2"></i>Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'messages.php' ? 'active' : ''; ?>" href="messages.php">
                    <i class="fas fa-envelope me-2"></i>Messages
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge bg-danger ms-2"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'settings.php' ? 'active' : ''; ?>" href="settings.php">
                    <i class="fas fa-cog me-2"></i>Settings
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link" href="../" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>View Website
                </a>
            </li>
        </ul>
    </div>
</div>