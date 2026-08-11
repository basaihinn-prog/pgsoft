<?php
require_once __DIR__ . '/includes/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit;
}

$agentCode = trim($_POST['agentCode'] ?? '');
$password = $_POST['senha'] ?? '';

if ($agentCode === '' || $password === '') {
    $_SESSION['login_error'] = 'Agent Code e Senha são obrigatórios.';
    header('Location: /index.php');
    exit;
}

$statement = $conn->prepare('SELECT id, agentcode, senha FROM agents WHERE agentcode = :agentcode LIMIT 1');
$statement->execute(['agentcode' => $agentCode]);
$agent = $statement->fetch();
$isPasswordValid = $agent && (str_starts_with($agent['senha'], '$2y$')
    ? password_verify($password, $agent['senha'])
    : hash_equals($agent['senha'], $password));

if (!$isPasswordValid) {
    $_SESSION['login_error'] = 'Agent Code ou senha inválidos.';
    header('Location: /index.php');
    exit;
}

session_regenerate_id(true);
$_SESSION['admin_id'] = $agent['id'];
$_SESSION['agentcode'] = $agent['agentcode'];

header('Location: /painel.php');
exit;
