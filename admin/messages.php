<?php
    session_start();

    // Block page for non-logged-in users
    if (!isset($_SESSION['admin_username'])) {
        header("Location: ../admin/login.php");
        exit;
    }

    // Prevent browser caching after logout
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
?>

<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->connect();

// Pagination settings
$perPageOptions = [5, 10, 15, 20];
$perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $perPageOptions) ? (int)$_GET['per_page'] : 5;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $perPage;

// Filters and search
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');
$searchBy = in_array($_GET['search_by'] ?? '', ['name', 'email']) ? $_GET['search_by'] : '';

$whereClauses = [];
$params = [];

// Apply read/unread filter
if ($filter === 'read') {
    $whereClauses[] = 'is_read = 1';
} elseif ($filter === 'unread') {
    $whereClauses[] = 'is_read = 0';
}

// Apply search filter
if (!empty($search) && $searchBy) {
    $whereClauses[] = "$searchBy LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

// Build WHERE SQL
$whereSQL = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

// Total count for pagination
$countStmt = $conn->prepare("SELECT COUNT(*) FROM contact_messages $whereSQL");
$countStmt->execute($params);
$totalMessages = $countStmt->fetchColumn();
$totalPages = ceil($totalMessages / $perPage);

// Fetch messages
$stmt = $conn->prepare("SELECT * FROM contact_messages $whereSQL ORDER BY created_at DESC LIMIT :offset, :limit");
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// AJAX: Mark as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $stmt = $conn->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
    $stmt->execute([':id' => $_POST['id']]);
    echo 'updated';
    exit;
}

// Single delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$_POST['delete_id']]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?deleted=1");
    exit;
}

// Bulk delete
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_ids'])) {
//     $ids = $_POST['selected_ids'];
//     if (is_array($ids) && count($ids)) {
//         $placeholders = implode(',', array_fill(0, count($ids), '?'));
//         $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id IN ($placeholders)");
//         $stmt->execute($ids);
//         header("Location: " . $_SERVER['PHP_SELF'] . "?bulk_deleted=1");
//         exit;
//     }
// }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Messages - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2>Contact Messages</h2>

                <form class="row mb-3" method="GET">
                    <div class="col-md-3">
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <?php foreach ($perPageOptions as $opt): ?>
                                <option value="<?= $opt ?>" <?= $perPage == $opt ? 'selected' : '' ?>><?= $opt ?> per page
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="filter" class="form-select" onchange="this.form.submit()">
                            <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All</option>
                            <option value="read" <?= $filter === 'read' ? 'selected' : '' ?>>Read</option>
                            <option value="unread" <?= $filter === 'unread' ? 'selected' : '' ?>>Unread</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="search_by" class="form-select">
                            <option value="name" <?= $searchBy === 'name' ? 'selected' : '' ?>>Search by Name</option>
                            <option value="email" <?= $searchBy === 'email' ? 'selected' : '' ?>>Search by Email</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                            value="<?= htmlspecialchars($search) ?>">
                    </div>
                </form>

                <form method="POST" action="">
                    <!-- <a href="export_messages.php" class="btn btn-success mb-3">Export to CSV</a> -->
                    <!-- <button type="submit" class="btn btn-danger mb-3">Delete Selected</button> -->

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <!-- <th><input type="checkbox" id="checkAll"></th> -->
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="messageTable">
                            <?php if (!empty($messages)): ?>
                                <?php foreach ($messages as $index => $msg): ?>
                                    <tr>
                                        <!-- <td><input type="checkbox" name="selected_ids[]" value="<?= $msg['id'] ?>"></td> -->
                                        <td><?= $offset + $index + 1 ?></td>
                                        <td><?= htmlspecialchars($msg['name']) ?></td>
                                        <td><?= htmlspecialchars($msg['email']) ?></td>
                                        <td><?= strlen($msg['message']) > 50 ? substr($msg['message'], 0, 50) . '...' : $msg['message'] ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($msg['created_at'])) ?></td>
                                        <td class="status-cell">
                                            <span
                                                class="badge <?= $msg['is_read'] == 0 ? 'bg-warning text-dark' : 'bg-success' ?>">
                                                <?= $msg['is_read'] == 0 ? 'Unread' : 'Read' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info view-btn"
                                                data-id="<?= $msg['id'] ?>" data-bs-toggle="modal"
                                                data-bs-target="#msgModal<?= $msg['id'] ?>">View</button>

                                            <!-- Delete Button trigger -->
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal<?= $msg['id'] ?>">
                                                Delete
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="deleteModal<?= $msg['id'] ?>" tabindex="-1"
                                                aria-labelledby="deleteModalLabel<?= $msg['id'] ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form method="POST" action="">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel<?= $msg['id'] ?>">
                                                                    Confirm Delete</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete this message from
                                                                <strong><?= htmlspecialchars($msg['name']) ?></strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="delete_id" value="<?= $msg['id'] ?>">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Yes,
                                                                    Delete</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>



                                        </td>
                                    </tr>
                                    <div class="modal fade" id="msgModal<?= $msg['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Message from <?= htmlspecialchars($msg['name']) ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Email:</strong> <?= htmlspecialchars($msg['email']) ?></p>
                                                    <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No messages found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>

                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link"
                                    href="?page=<?= $i ?>&per_page=<?= $perPage ?>&filter=<?= $filter ?>&search_by=<?= $searchBy ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>


    <script src="js/script.js"></script>
    <script>
        $(document).ready(function () {
            $('.view-btn').on('click', function () {
                const messageId = $(this).data('id');
                const row = $(this).closest('tr');

                $.ajax({
                    type: 'POST',
                    url: '', // current page (messages.php)
                    data: { id: messageId },
                    success: function () {
                        row.find('.status-cell').html('<span class="badge bg-success">Read</span>');
                    },
                    error: function (xhr) {
                        console.error('Error marking as read:', xhr.responseText);
                    }
                });
            });
        });
    </script>

</body>

</html>