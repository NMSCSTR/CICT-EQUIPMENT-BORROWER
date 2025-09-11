<?php
// controller/login.php
// Authenticate user via email or student_id and password

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: /login.php');
	exit;
}

require_once __DIR__ . '/../config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

function get_post($key) {
	return isset($_POST[$key]) ? trim((string)$_POST[$key]) : '';
}

$identifier = get_post('username'); // email or student_id
$password = get_post('password');

if ($identifier === '' || $password === '') {
	header('Location: ../login.php?error=' . urlencode('Please enter your username and password.'));
	exit;
}

// Fetch user by email or student_id
$stmt = mysqli_prepare($conn, 'SELECT user_id, user_type, name, email, contact_number, password, student_id FROM users WHERE email = ? OR student_id = ? LIMIT 1');
if (!$stmt) {
	header('Location: ../login.php?error=' . urlencode('Server error.'));
	exit;
}
mysqli_stmt_bind_param($stmt, 'ss', $identifier, $identifier);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);

if (!$user) {
	header('Location: ../login.php?error=' . urlencode('Account not found.'));
	exit;
}

// Verify password
if (!password_verify($password, $user['password'])) {
	header('Location: ../login.php?error=' . urlencode('Invalid credentials.'));
	exit;
}

// Successful login
$_SESSION['user_id'] = (int)$user['user_id'];
$_SESSION['user_type'] = $user['user_type'];
$_SESSION['name'] = $user['name'];
$_SESSION['email'] = $user['email'];
$_SESSION['student_id'] = $user['student_id'];

// Optional: regenerate session ID to prevent fixation
session_regenerate_id(true);

header('Location: ../dashboard.php?success=' . urlencode('Welcome back, ' . $user['name'] . '!'));
exit; 