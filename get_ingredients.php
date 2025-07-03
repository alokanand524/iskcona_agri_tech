<?php
require_once 'config/database.php';
$db = (new Database())->connect();

$product_id = intval($_GET['product_id'] ?? 0);
$stmt = $db->prepare("SELECT crop, active_ingredient, dosage FROM product_ingredients WHERE product_id = ?");
$stmt->execute([$product_id]);
header('Content-Type: application/json');
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
