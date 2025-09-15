<?php
session_start();
if (!isset($_SESSION['user_id'])) {
	http_response_code(401);
	echo json_encode(['error' => 'Unauthorized']);
	exit;
}
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

$sql = 'SELECT equipment_id, equipment_name, description, quantity_total, quantity_available, equipment_condition FROM equipment ORDER BY equipment_name ASC';
$result = mysqli_query($conn, $sql);
if (!$result) {
	echo json_encode(['data' => [], 'error' => 'Query failed']);
	exit;
}
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
	$data[] = $row;
}
echo json_encode(['data' => $data]); 