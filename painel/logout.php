<?php
session_start();

// Clear all cookies
setcookie('admin_id', '', time() - 3600, '/');
setcookie('admin_pass', '', time() - 3600, '/');
setcookie('auth', '', time() - 3600, '/');
setcookie('agentcode', '', time() - 3600, '/');
setcookie('token', '', time() - 3600, '/');

// Clear session
$_SESSION = [];
session_destroy();

// Redirect to login
header('Location: /index.php');
exit;
?>
