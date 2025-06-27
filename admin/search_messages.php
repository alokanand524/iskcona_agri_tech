<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->connect();

$search = trim($_POST['search'] ?? '');

$sql = "SELECT * FROM contact_messages 
        WHERE name LIKE :search OR email LIKE :search 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([':search' => "%$search%"]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $index => $msg):
?>
    <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($msg['name']) ?></td>
        <td><?= htmlspecialchars($msg['email']) ?></td>
        <td><?= substr($msg['message'], 0, 50) ?>...</td>
        <td><?= $msg['is_read'] ? 'Read' : 'Unread' ?></td>
        <td><!-- actions --></td>
    </tr>
<?php endforeach; ?>
