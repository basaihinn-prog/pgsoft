<?php
error_reporting(0);
ini_set('display_errors', 1);

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agentCode = isset($_POST['agentCode']) ? trim($_POST['agentCode']) : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    
    if (empty($agentCode) || empty($senha)) {
        $error = 'Agent Code e Senha são obrigatórios';
    } else {
        try {
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
                setcookie('admin_id', $agent['id'], time() + (86400 * 30), '/');
                setcookie('admin_pass', $agent['senha'], time() + (86400 * 30), '/');
                setcookie('auth', 'admin_in', time() + (86400 * 30), '/');
                setcookie('agentcode', $agent['agentcode'], time() + (86400 * 30), '/');
                
                header('Location: /painel.php');
                exit;
            } else {
                $error = 'Agent Code ou Senha inválidos';
            }
            
            $conn = null;
        } catch (PDOException $e) {
            $error = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
        }
    }
}

// If there's an error, redirect back to login with error message
if (!empty($error)) {
    session_start();
    $_SESSION['login_error'] = $error;
    header('Location: /index.php');
    exit;
}
?>
