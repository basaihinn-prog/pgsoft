<?php
session_start();
error_reporting(1);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agentCode = isset($_POST['agentCode']) ? trim($_POST['agentCode']) : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    
    // Validate input
    if (empty($agentCode) || empty($senha)) {
        $_SESSION['login_error'] = 'Agent Code e Senha são obrigatórios';
        header('Location: /index.php');
        exit;
    }
    
    // Simple authentication: accept any non-empty credentials
    // In production, this would check against a database
    
    // Generate a secure session token
    $token = bin2hex(random_bytes(32));
    
    // Set authentication cookies
    setcookie('admin_id', '1', time() + (86400 * 30), '/', '', false, true);
    setcookie('admin_pass', base64_encode($senha), time() + (86400 * 30), '/', '', false, true);
    setcookie('auth', 'admin_in', time() + (86400 * 30), '/', '', false, true);
    setcookie('agentcode', $agentCode, time() + (86400 * 30), '/', '', false, true);
    setcookie('token', $token, time() + (86400 * 30), '/', '', false, true);
    
    // Also set in SESSION for immediate availability
    $_SESSION['admin_id'] = '1';
    $_SESSION['agentcode'] = $agentCode;
    $_SESSION['auth'] = 'admin_in';
    $_SESSION['token'] = $token;
    $_SESSION['login_time'] = date('Y-m-d H:i:s');
    
    // Redirect to dashboard
    header('Location: /painel.php');
    exit;
} else {
    // If no POST data, just show login page
    header('Location: /index.php');
    exit;
}
?>
