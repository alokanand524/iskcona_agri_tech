<?php
session_start();
require_once '../config/database.php';

// Get form input
$username = $_POST['username'] ?? '';
$old_password = $_POST['old_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    die("Not authorized");
}

// Connect to DB
$db = new Database();
$pdo = $db->connect();

try {
    // Get admin row
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        die("Admin not found.");
    }

    // Default query: update only username
    $updateQuery = "UPDATE admin_users SET username = ?";
    $params = [$username];

    // If password change is requested
    if (!empty($old_password) && !empty($new_password)) {
        if (!password_verify($old_password, $admin['password'])) {
            die("❌ Old password is incorrect.");
        }

        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $updateQuery .= ", password = ?";
        $params[] = $hashedPassword;
    }

    $updateQuery .= " WHERE id = ?";
    $params[] = $admin_id;

    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute($params);

    $_SESSION['admin_username'] = $username;
    header("Location: dashboard.php?msg=Profile updated successfully");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
