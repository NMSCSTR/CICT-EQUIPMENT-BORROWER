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

$equipment_id = isset($_POST['equipment_id']) ? (int)$_POST['equipment_id'] : 0;
if ($equipment_id <= 0) {
    header('Location: ../equipment.php?error=' . urlencode('Invalid equipment.'));
    exit;
}

// Check related borrow_transaction rows
$stmt = mysqli_prepare($conn, 'SELECT 1 FROM borrow_transaction WHERE equipment_id = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $equipment_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$hasBorrow = mysqli_stmt_num_rows($stmt) > 0;
mysqli_stmt_close($stmt);

if ($hasBorrow) {
    header('Location: ../equipment.php?error=' . urlencode('Cannot delete equipment with existing borrow transactions.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'DELETE FROM equipment WHERE equipment_id = ?');
mysqli_stmt_bind_param($stmt, 'i', $equipment_id);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: ../equipment.php?error=' . urlencode('Failed to delete equipment.'));
    exit;
}
mysqli_stmt_close($stmt);

header('Location: ../equipment.php?success=' . urlencode('Equipment deleted.'));
exit;

