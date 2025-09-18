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
$expected_return_date = isset($_POST['expected_return_date']) ? trim((string)$_POST['expected_return_date']) : '';
if ($request_id <= 0) { header('Location: ../item_request.php?error=' . urlencode('Invalid request.')); exit; }

// Begin transaction
mysqli_begin_transaction($conn);
try {
    // Load request
    $stmt = mysqli_prepare($conn, 'SELECT user_id, equipment_id, quantity_requested, status FROM item_request WHERE request_id = ? FOR UPDATE');
    mysqli_stmt_bind_param($stmt, 'i', $request_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $req = $res ? $res->fetch_assoc() : null;
    mysqli_stmt_close($stmt);

    if (!$req) { throw new Exception('Request not found.'); }
    if (strtolower((string)$req['status']) === 'approved') { throw new Exception('Already approved.'); }

    $user_id = (int)$req['user_id'];
    $equipment_id = (int)$req['equipment_id'];
    $qty = (int)$req['quantity_requested'];

    // Check and lock equipment
    $stmt = mysqli_prepare($conn, 'SELECT quantity_available FROM equipment WHERE equipment_id = ? FOR UPDATE');
    mysqli_stmt_bind_param($stmt, 'i', $equipment_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $eq = $res ? $res->fetch_assoc() : null;
    mysqli_stmt_close($stmt);
    if (!$eq) { throw new Exception('Equipment not found.'); }
    if ((int)$eq['quantity_available'] < $qty) { throw new Exception('Insufficient availability.'); }

    // Deduct availability
    $stmt = mysqli_prepare($conn, 'UPDATE equipment SET quantity_available = quantity_available - ? WHERE equipment_id = ?');
    mysqli_stmt_bind_param($stmt, 'ii', $qty, $equipment_id);
    if (!mysqli_stmt_execute($stmt)) { throw new Exception('Failed to update availability.'); }
    mysqli_stmt_close($stmt);

    // Insert borrow transaction
    $now = date('Y-m-d H:i:s');
    $status = 'approved';
    // expected_return_date requires db column; if missing, this will error.
    $stmt = mysqli_prepare($conn, 'INSERT INTO borrow_transaction (user_id, equipment_id, borrow_date, return_date, quantity_borrowed, status, purpose, class_schedule_id, expected_return_date) VALUES (?, ?, ?, NULL, ?, ?, NULL, NULL, ?)');
    mysqli_stmt_bind_param($stmt, 'iisiss', $user_id, $equipment_id, $now, $qty, $status, $expected_return_date);
    if (!mysqli_stmt_execute($stmt)) { throw new Exception('Failed to create transaction.'); }
    mysqli_stmt_close($stmt);

    // Mark request approved
    $stmt = mysqli_prepare($conn, 'UPDATE item_request SET status = ? WHERE request_id = ?');
    $status2 = 'approved';
    mysqli_stmt_bind_param($stmt, 'si', $status2, $request_id);
    if (!mysqli_stmt_execute($stmt)) { throw new Exception('Failed to update request.'); }
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);
    header('Location: ../item_request.php?success=' . urlencode('Request approved and transaction created.'));
    exit;
} catch (Exception $e) {
    mysqli_rollback($conn);
    header('Location: ../item_request.php?error=' . urlencode($e->getMessage()));
    exit;
}

