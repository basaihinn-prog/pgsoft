<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);

// Check if user is authenticated
if (empty($_COOKIE['auth']) && empty($_SESSION['auth'])) {
    header('Location: /index.php');
    exit;
}

// Set session from cookie if not already set
if (empty($_SESSION['auth']) && $_COOKIE['auth'] == 'admin_in') {
    $_SESSION['auth'] = 'admin_in';
    $_SESSION['admin_id'] = $_COOKIE['admin_id'] ?? '';
    $_SESSION['agentcode'] = $_COOKIE['agentcode'] ?? '';
}
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <?php include "template/head.php"; ?>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <?php include "template/aside.php"; ?>
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include "template/top.php"; ?>
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="py-3 mb-4">Dashboard</h4>
                        
                        <div class="row">
                            <div class="col-lg-8 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Bem-vindo ao Painel!</h5>
                                        <p class="card-text">Olá, <strong><?php echo htmlspecialchars($_SESSION['agentcode'] ?? 'Agent'); ?></strong></p>
                                        <p class="card-text">Use o menu lateral para navegar entre as opções do sistema.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Informações da Sessão</h5>
                                        <ul class="list-unstyled">
                                            <li><strong>ID:</strong> <?php echo htmlspecialchars($_SESSION['admin_id'] ?? 'N/A'); ?></li>
                                            <li><strong>Agent Code:</strong> <?php echo htmlspecialchars($_SESSION['agentcode'] ?? 'N/A'); ?></li>
                                            <li><strong>Status:</strong> <span class="badge bg-success">Online</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">
                            ©
                            <script>
                            document.write(new Date().getFullYear())
                            </script>
                            , Todos os direitos reservados
                        </div>
                    </div>
                </footer>
            </div>
            <!-- / Layout page -->
        </div>
    </div>
    <?php include "template/footer.php"; ?>
</body>

</html>
