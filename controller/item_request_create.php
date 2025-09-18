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
$equipment_id = (int)post('equipment_id');
$qty = (int)post('quantity_requested');
$unavailable_action = post('unavailable_action');

if ($equipment_id <= 0 || $qty <= 0) {
    header('Location: ../student_dashboard.php?error=' . urlencode('Invalid request.'));
    exit;
}

// Check availability
$stmt = mysqli_prepare($conn, 'SELECT quantity_available FROM equipment WHERE equipment_id = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $equipment_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$eq = $res ? $res->fetch_assoc() : null;
mysqli_stmt_close($stmt);

if (!$eq) {
    header('Location: ../student_dashboard.php?error=' . urlencode('Equipment not found.'));
    exit;
}

if ((int)$eq['quantity_available'] < $qty) {
    if ($unavailable_action === 'waitlist') {
        // Create item_request with status 'waitlist'
        $stmt = mysqli_prepare($conn, 'INSERT INTO item_request (user_id, equipment_id, quantity_requested, request_date, status, approved_by) VALUES (?, ?, ?, NOW(), ?, NULL)');
        $status = 'waitlist';
        mysqli_stmt_bind_param($stmt, 'iiis', $user_id, $equipment_id, $qty, $status);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ../student_dashboard.php?success=' . urlencode('Added to waitlist.'));
        exit;
    } else {
        header('Location: ../student_dashboard.php?error=' . urlencode('Insufficient availability.'));
        exit;
    }
}

// Enough available: create request with status 'pending'
$stmt = mysqli_prepare($conn, 'INSERT INTO item_request (user_id, equipment_id, quantity_requested, request_date, status, approved_by) VALUES (?, ?, ?, NOW(), ?, NULL)');
$status = 'pending';
mysqli_stmt_bind_param($stmt, 'iiis', $user_id, $equipment_id, $qty, $status);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header('Location: ../student_dashboard.php?success=' . urlencode('Request submitted.'));
exit;

