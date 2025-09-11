<?php
// controller/registration.php
// Handles registration form submission

// Enforce POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: /register.php');
	exit;
}

// Include DB config
require_once __DIR__ . '/../config.php';

// Helper: sanitize
function get_post($key) {
	return isset($_POST[$key]) ? trim((string)$_POST[$key]) : '';
}

$user_type = get_post('user_type');
$name = get_post('name');
$email = get_post('email');
$contact_number = get_post('contact_number');
$student_id = get_post('student_id');
$password = get_post('password');
$confirm_password = get_post('confirm_password');

// Basic validations
$errors = [];
if ($user_type === '') { $errors[] = 'User type is required.'; }
if ($name === '') { $errors[] = 'Name is required.'; }
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Valid email is required.'; }
if ($password === '' || strlen($password) < 6) { $errors[] = 'Password must be at least 6 characters.'; }
if ($password !== $confirm_password) { $errors[] = 'Passwords do not match.'; }

if (!empty($errors)) {
	// Redirect back with errors
	$query = http_build_query([
		'error' => implode(' ', $errors)
	]);
	header('Location: /register.php?' . $query);
	exit;
}

// Check email uniqueness
$stmt = mysqli_prepare($conn, 'SELECT user_id FROM users WHERE email = ? LIMIT 1');
if (!$stmt) {
	header('Location: /register.php?error=' . urlencode('Server error.'));
	exit;
}
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
	mysqli_stmt_close($stmt);
	header('Location: /register.php?error=' . urlencode('Email is already registered.'));
	exit;
}
mysqli_stmt_close($stmt);

// Hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = mysqli_prepare($conn, 'INSERT INTO users (user_type, name, email, contact_number, password, student_id) VALUES (?,?,?,?,?,?)');
if (!$stmt) {
	header('Location: ../register.php?error=' . urlencode('Server error.'));
	exit;
}
mysqli_stmt_bind_param($stmt, 'ssssss', $user_type, $name, $email, $contact_number, $hash, $student_id);
$ok = mysqli_stmt_execute($stmt);
if (!$ok) {
	mysqli_stmt_close($stmt);
	header('Location: ../register.php?error=' . urlencode('Could not create account.'));
	exit;
}
$user_id = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

// Success: redirect to login with message
header('Location: ../login.php?success=' . urlencode('Account created. Please sign in.'));
exit;
