<?php
    require_once __DIR__ . '/../config/config.php';

    requireLogin();

    // Get statistics
    $stats = [];

    // Total products
    $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE is_active = TRUE");
    $stats['total_products'] = $stmt->fetch()['count'];

    // Products by category
    $stmt = $db->query("SELECT category, COUNT(*) as count FROM products WHERE is_active = TRUE GROUP BY category");
    $productsByCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Unread messages
    $stmt = $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = FALSE");
    $stats['unread_messages'] = $stmt->fetch()['count'];

    // Recent messages
    $stmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
    $recentMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    include 'includes/header.php';
?>


<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-calendar"></i> This Week
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Products
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $stats['total_products']; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-seedling fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Insecticides
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php
                                        $insecticides = array_filter($productsByCategory, function ($item) {
                                            return $item['category'] == 'insecticide';
                                        });
                                        echo !empty($insecticides) ? reset($insecticides)['count'] : 0;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-bug fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Herbicides
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php
                                        $herbicides = array_filter($productsByCategory, function ($item) {
                                            return $item['category'] == 'herbicide';
                                        });
                                        echo !empty($herbicides) ? reset($herbicides)['count'] : 0;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-leaf fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Unread Messages
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $stats['unread_messages']; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-envelope fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Contact Messages</h6>
                            <a href="messages.php" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($recentMessages)): ?>
                                <p class="text-muted">No messages yet.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>message</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentMessages as $message): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                                                    <td><?php echo htmlspecialchars(substr($message['message'], 0, 30)) . '...'; ?>
                                                    </td>
                                                    <td><?php echo date('M j, Y', strtotime($message['created_at'])); ?></td>
                                                    <td>
                                                        <?php if ($message['is_read']): ?>
                                                            <span class="badge bg-success">Read</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning">Unread</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="products.php?action=add" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Add New Product
                                </a>
                                <a href="pages.php" class="btn btn-info">
                                    <i class="fas fa-edit me-2"></i>Edit Pages
                                </a>
                                <a href="messages.php" class="btn btn-warning">
                                    <i class="fas fa-envelope me-2"></i>View Messages
                                </a>
                                <a href="settings.php" class="btn btn-secondary">
                                    <i class="fas fa-cog me-2"></i>Site Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>