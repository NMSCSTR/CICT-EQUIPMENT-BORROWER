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

$request_id = isset($_POST['request_id']) ? (int)$_POST['request_id'] : 0;
if ($request_id <= 0) {
    header('Location: ../item_request.php?error=' . urlencode('Invalid request.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'DELETE FROM item_request WHERE request_id = ?');
mysqli_stmt_bind_param($stmt, 'i', $request_id);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: ../item_request.php?error=' . urlencode('Failed to delete request.'));
    exit;
}
mysqli_stmt_close($stmt);

header('Location: ../item_request.php?success=' . urlencode('Request deleted.'));
exit;

