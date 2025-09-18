<?php
// controller/logout.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enforce POST to avoid accidental logouts via link prefetch
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

// Unset all session variables
$_SESSION = [];

// Delete session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

// Destroy the session
session_destroy();

header('Location: ../login.php?success=' . urlencode('You have been logged out.'));
exit;

