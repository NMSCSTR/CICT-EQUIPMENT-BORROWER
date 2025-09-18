<?php
// controller/user_update.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../users.php');
    exit;
}

require_once __DIR__ . '/../config.php';

function post($k){ return isset($_POST[$k]) ? trim((string)$_POST[$k]) : ''; }

$user_id = (int)post('user_id');
$user_type = strtolower(post('user_type'));
$name = post('name');
$email = post('email');
$contact_number = post('contact_number');
$student_id = post('student_id');

if ($user_id <= 0 || $name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../user_edit.php?user_id=' . $user_id . '&error=' . urlencode('Invalid input.'));
    exit;
}

// Do not allow changing non-admin to admin through edit from users list page? Keep allowed as requested.

// Ensure email is unique for other users
$stmt = mysqli_prepare($conn, 'SELECT user_id FROM users WHERE email = ? AND user_id <> ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'si', $email, $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    header('Location: ../user_edit.php?user_id=' . $user_id . '&error=' . urlencode('Email already in use.'));
    exit;
}
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, 'UPDATE users SET user_type = ?, name = ?, email = ?, contact_number = ?, student_id = ? WHERE user_id = ?');
mysqli_stmt_bind_param($stmt, 'sssssi', $user_type, $name, $email, $contact_number, $student_id, $user_id);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: ../user_edit.php?user_id=' . $user_id . '&error=' . urlencode('Failed to update user.'));
    exit;
}
mysqli_stmt_close($stmt);

header('Location: ../users.php?success=' . urlencode('User updated.'));
exit;

