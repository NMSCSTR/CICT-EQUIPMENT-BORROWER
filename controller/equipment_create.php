<?php
session_start();
if (!isset($_SESSION['user_id'])) {
	header('Location: ../login.php?error=' . urlencode('Please sign in to continue.'));
	exit;
}
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: ../equipment.php');
	exit;
}

function post($k){ return isset($_POST[$k]) ? trim((string)$_POST[$k]) : ''; }
$name = post('equipment_name');
$desc = post('description');
$total = (int)post('quantity_total');
$available = (int)post('quantity_available');
$condition = post('equipment_condition');

$errors = [];
if ($name === '') { $errors[] = 'Equipment name is required.'; }
if ($total < 0) { $errors[] = 'Total quantity cannot be negative.'; }
if ($available < 0) { $errors[] = 'Available quantity cannot be negative.'; }
if ($available > $total) { $errors[] = 'Available cannot exceed total.'; }

if ($errors) {
	header('Location: ../equipment.php?error=' . urlencode(implode(' ', $errors)));
	exit;
}

$stmt = mysqli_prepare($conn, 'INSERT INTO equipment (equipment_name, description, quantity_total, quantity_available, equipment_condition) VALUES (?,?,?,?,?)');
if (!$stmt) {
	header('Location: ../equipment.php?error=' . urlencode('Server error.'));
	exit;
}
mysqli_stmt_bind_param($stmt, 'ssiis', $name, $desc, $total, $available, $condition);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (!$ok) {
	header('Location: ../equipment.php?error=' . urlencode('Could not save equipment.'));
	exit;
}

header('Location: ../equipment.php?success=' . urlencode('Equipment added.'));
exit; 