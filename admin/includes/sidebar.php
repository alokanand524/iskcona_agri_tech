<?php
// header("Cache-Control: no-store, no-cache, must-revalidate");
// header("Pragma: no-cache");
// header("Expires: 0");

// Block page for non-logged-in users
if (!isset($_SESSION['admin_username'])) {
    header("Location: ../admin/login.php");
    exit;
}



require_once '../config/database.php';
$db = new Database();
$conn = $db->connect();

$currentPage = basename($_SERVER['PHP_SELF']);

// Fetch unread messages count
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
$stmt->execute();
$unreadCount = $stmt->fetch()['count'] ?? 0;
?>

<style>
    .admin-sidebar {
    top: 85px;
    left: 0;
    width: 215px;
    height: 100vh;
    z-index: 1050;
    transition: transform 0.3s ease;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

@media (max-width: 767.98px) {
    .admin-sidebar {
        transform: translateX(-100%);
        top:67px;
    }

    .admin-sidebar.show {
        transform: translateX(0);
    }
}

</style>

<!-- Sidebar Toggle Button -->
<button id="sidebarToggle" class="btn btn-dark d-md-none position-fixed" style="top: 19px; left: 3px; z-index: 1100; width:auto;">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<nav id="adminSidebar" class="admin-sidebar bg-dark text-white position-fixed">

    <div class="p-3">
        <h5 class="text-white mb-4">Admin Panel</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $currentPage == 'products.php' ? 'active' : ''; ?>" href="products.php">
                    <i class="fas fa-seedling me-1"></i>Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $currentPage == 'messages.php' ? 'active' : ''; ?>" href="messages.php">
                    <i class="fas fa-envelope me-2"></i>Messages
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge bg-danger ms-1"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $currentPage == 'blog-list.php' ? 'active' : ''; ?>" href="blog-list.php">
                    <i class="fas fa-globe me-2"></i>Blog
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $currentPage == 'settings.php' ? 'active' : ''; ?>" href="settings.php">
                    <i class="fas fa-cog me-1"></i>Settings
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-white" href="../" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>View Website
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Sidebar JS -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.getElementById("adminSidebar");
        const toggleBtn = document.getElementById("sidebarToggle");
        let sidebarVisible = window.innerWidth >= 768;

        function hideSidebar() {
            sidebar.style.transform = "translateX(-100%)";
        }

        function showSidebar() {
            sidebar.style.transform = "translateX(0)";
        }

        // Initial state for mobile
        if (!sidebarVisible) hideSidebar();

        toggleBtn.addEventListener("click", function () {
            if (sidebarVisible) {
                hideSidebar();
            } else {
                showSidebar();
            }
            sidebarVisible = !sidebarVisible;
        });

        // Auto-close sidebar when screen resizes to mobile
        window.addEventListener("resize", function () {
            if (window.innerWidth < 768) {
                sidebarVisible = false;
                hideSidebar();
            } else {
                sidebarVisible = true;
                showSidebar();
            }
        });
    });
</script>

<!-- Push Content on Desktop Only -->
<style>
    @media (min-width: 768px) {
        .main-content {
            margin-left: 250px;
        }
    }

    .nav-link.active {
        background-color: #0d6efd !important;
        color: #fff !important;
        border-radius: 0.375rem;
    }

    #adminSidebar {
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }
</style>
