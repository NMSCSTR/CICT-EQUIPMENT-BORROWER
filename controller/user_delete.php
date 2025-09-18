<?php
// controller/user_delete.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../users.php');
    exit;
}

require_once __DIR__ . '/../config.php';

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
if ($user_id <= 0) {
    header('Location: ../users.php?error=' . urlencode('Invalid user.'));
    exit;
}

// Prevent deleting admins just in case
$stmt = mysqli_prepare($conn, "SELECT user_type FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = $res ? $res->fetch_assoc() : null;
mysqli_stmt_close($stmt);
if (!$user) {
    header('Location: ../users.php?error=' . urlencode('User not found.'));
    exit;
}
if (strtolower($user['user_type']) === 'admin') {
    header('Location: ../users.php?error=' . urlencode('Cannot delete admin user.'));
    exit;
}

// Check for related records (borrow_transaction, item_request) to avoid FK issues
$stmt = mysqli_prepare($conn, 'SELECT 1 FROM borrow_transaction WHERE user_id = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$hasBorrow = mysqli_stmt_num_rows($stmt) > 0;
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, 'SELECT 1 FROM item_request WHERE user_id = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$hasRequest = mysqli_stmt_num_rows($stmt) > 0;
mysqli_stmt_close($stmt);

if ($hasBorrow || $hasRequest) {
    header('Location: ../users.php?error=' . urlencode('User has related records and cannot be deleted.'));
    exit;
}

// Proceed delete
$stmt = mysqli_prepare($conn, 'DELETE FROM users WHERE user_id = ?');
mysqli_stmt_bind_param($stmt, 'i', $user_id);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: ../users.php?error=' . urlencode('Failed to delete user.'));
    exit;
}
mysqli_stmt_close($stmt);

header('Location: ../users.php?success=' . urlencode('User deleted.'));
exit;

