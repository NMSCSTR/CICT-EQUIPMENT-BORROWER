<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php?error=' . urlencode('Please sign in to continue.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../item_request.php');
    exit;
}

require_once __DIR__ . '/../config.php';

function post($k){ return isset($_POST[$k]) ? trim((string)$_POST[$k]) : ''; }

$request_id = (int)post('request_id');
$user_id = (int)post('user_id');
$equipment_id = (int)post('equipment_id');
$quantity_requested = (int)post('quantity_requested');
$status = post('status');
$approved_by = post('approved_by') === '' ? null : (int)post('approved_by');

if ($request_id <= 0 || $user_id <= 0 || $equipment_id <= 0 || $quantity_requested <= 0) {
    header('Location: ../item_request_edit.php?request_id=' . $request_id . '&error=' . urlencode('Invalid input.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'UPDATE item_request SET user_id = ?, equipment_id = ?, quantity_requested = ?, status = ?, approved_by = ? WHERE request_id = ?');
mysqli_stmt_bind_param($stmt, 'iiiisi', $user_id, $equipment_id, $quantity_requested, $status, $approved_by, $request_id);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: ../item_request_edit.php?request_id=' . $request_id . '&error=' . urlencode('Failed to update request.'));
    exit;
}
mysqli_stmt_close($stmt);

header('Location: ../item_request.php?success=' . urlencode('Request updated.'));
exit;

