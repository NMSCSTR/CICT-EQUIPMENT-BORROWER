<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php?error=' . urlencode('Please sign in to continue.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../equipment.php');
    exit;
}

require_once __DIR__ . '/../config.php';

function post($k){ return isset($_POST[$k]) ? trim((string)$_POST[$k]) : ''; }

$equipment_id = (int)post('equipment_id');
$name = post('equipment_name');
$desc = post('description');
$total = (int)post('quantity_total');
$available = (int)post('quantity_available');
$condition = post('equipment_condition');

if ($equipment_id <= 0 || $name === '') {
    header('Location: ../equipment_edit.php?equipment_id=' . $equipment_id . '&error=' . urlencode('Invalid input.'));
    exit;
}
if ($total < 0 || $available < 0 || $available > $total) {
    header('Location: ../equipment_edit.php?equipment_id=' . $equipment_id . '&error=' . urlencode('Please check quantities.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'UPDATE equipment SET equipment_name = ?, description = ?, quantity_total = ?, quantity_available = ?, equipment_condition = ? WHERE equipment_id = ?');
mysqli_stmt_bind_param($stmt, 'ssiisi', $name, $desc, $total, $available, $condition, $equipment_id);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: ../equipment_edit.php?equipment_id=' . $equipment_id . '&error=' . urlencode('Failed to update equipment.'));
    exit;
}
mysqli_stmt_close($stmt);

header('Location: ../equipment.php?success=' . urlencode('Equipment updated.'));
exit;

