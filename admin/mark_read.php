<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
    $stmt->execute([':id' => $_POST['id']]);
    echo 'updated';
} else {
    http_response_code(400);
    echo 'Invalid request';
}
