<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php?error=' . urlencode('Please sign in to continue.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../student_dashboard.php');
    exit;
}

require_once __DIR__ . '/../config.php';

function post($k){ return isset($_POST[$k]) ? trim((string)$_POST[$k]) : ''; }

$user_id = (int)$_SESSION['user_id'];
$suggestion = post('suggestion');
$reason = post('reason');

if ($suggestion === '') {
    header('Location: ../student_dashboard.php?error=' . urlencode('Suggestion cannot be empty.'));
    exit;
}

// Store as a notification entry of type 'suggestion'
$stmt = mysqli_prepare($conn, 'INSERT INTO notification (user_id, message, notification_type, sent_date) VALUES (?, ?, ?, NOW())');
$type = 'suggestion';
$message = $suggestion . ($reason !== '' ? (' — ' . $reason) : '');
mysqli_stmt_bind_param($stmt, 'iss', $user_id, $message, $type);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header('Location: ../student_dashboard.php?success=' . urlencode('Suggestion sent. Thank you!'));
exit;

