<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/database.php';

// ✅ Create database connection
$db = new Database();
$conn = $db->connect();

// ✅ Use PDO-style function
function updateSetting($conn, $section_key, $content) {
    $stmt = $conn->prepare("UPDATE site_settings SET content = :content WHERE section_key = :key");
    $stmt->execute([
        ':content' => $content,
        ':key' => $section_key
    ]);
}

// ✅ Handle POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    updateSetting($conn, 'contact_location', $_POST['contact_location']);
    updateSetting($conn, 'contact_phone', $_POST['contact_phone']);
    updateSetting($conn, 'contact_email', $_POST['contact_email']);

    header("Location: settings.php?success=1");
    exit();
}
?>
