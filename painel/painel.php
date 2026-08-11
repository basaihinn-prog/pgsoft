<?php
require_once __DIR__ . '/includes/auth.php';
$agentCode = $_SESSION['agentcode'];
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
            <div class="layout-page">
                <?php include "template/top.php"; ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="py-3 mb-4">Dashboard</h4>
                        <div class="row">
                            <div class="col-lg-8 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Bem-vindo ao Painel Administrativo!</h5>
                                        <p class="card-text">Olá, <strong><?php echo htmlspecialchars($agentCode, ENT_QUOTES, 'UTF-8'); ?></strong></p>
                                        <p class="card-text">Use o menu lateral para navegar entre as opções do sistema.</p>
                                        <p class="card-text text-muted">Sistema PGSoft - Gerenciador de Jogos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Informações da Sessão</h5>
                                        <ul class="list-unstyled">
                                            <li><strong>Agent Code:</strong> <?php echo htmlspecialchars($agentCode, ENT_QUOTES, 'UTF-8'); ?></li>
                                            <li><strong>Status:</strong> <span class="badge bg-success">Online</span></li>
                                            <li><strong>Sessão Ativa:</strong> <span class="badge bg-info">Sim</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header"><h5 class="card-title">Ações Rápidas</h5></div>
                                    <div class="card-body">
                                        <a href="/jogos.php" class="btn btn-primary"><i class="bx bx-game"></i> Jogos</a>
                                        <a href="/agents.php" class="btn btn-info"><i class="bx bx-user"></i> Agentes</a>
                                        <a href="/logout.php" class="btn btn-danger"><i class="bx bx-log-out"></i> Sair</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">© <script>document.write(new Date().getFullYear())</script>, Todos os direitos reservados</div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <?php include "template/footer.php"; ?>
</body>

</html>
