<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agentCode = isset($_POST['agentCode']) ? trim($_POST['agentCode']) : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    
    // Validate input
    if (empty($agentCode) || empty($senha)) {
        $_SESSION['login_error'] = 'Agent Code e Senha são obrigatórios';
        header('Location: /index.php');
        exit;
    }
    
    try {
        // Database connection
        $host = "localhost";
        $dbname = "pgsoft";
        $user = "postgres";
        $password = "root123";
        
        $conn = new PDO("pgsql:host={$host};port=5432;dbname={$dbname}", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check agent credentials
        $stmt = $conn->prepare("SELECT id, agentcode, senha FROM agents WHERE agentcode = :agentcode AND senha = :senha LIMIT 1");
        $stmt->execute([
            ':agentcode' => $agentCode,
            ':senha' => $senha
        ]);
        
        if ($stmt->rowCount() > 0) {
            $agent = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Set authentication cookies
            setcookie('admin_id', $agent['id'], time() + (86400 * 30), '/', '', false, true);
            setcookie('admin_pass', $agent['senha'], time() + (86400 * 30), '/', '', false, true);
            setcookie('auth', 'admin_in', time() + (86400 * 30), '/', '', false, true);
            setcookie('agentcode', $agent['agentcode'], time() + (86400 * 30), '/', '', false, true);
            
            // Also set in SESSION for immediate availability
            $_SESSION['admin_id'] = $agent['id'];
            $_SESSION['agentcode'] = $agent['agentcode'];
            $_SESSION['auth'] = 'admin_in';
            
            $conn = null;
            
            // Redirect to dashboard
            header('Location: /painel.php');
            exit;
        } else {
            $_SESSION['login_error'] = 'Agent Code ou Senha inválidos';
            $conn = null;
            header('Location: /index.php');
            exit;
        }
        
    } catch (PDOException $e) {
        $_SESSION['login_error'] = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
        header('Location: /index.php');
        exit;
    }
} else {
    // If no POST data, just show login page
    header('Location: /index.php');
    exit;
}
?>
