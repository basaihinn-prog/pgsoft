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

                        <div class="card">

                            <div class="card-body">
                                <div class="card-title header-elements">
                                    <h5 class="m-0 me-2">Add New Users</h5>
                                </div>
                                <?php
								$data = [];

								$act = $_GET['act'];
								if ($act == "edit") {
									$id = $_GET['id'];
									$users = getById("users", $id);
								}
								?>

                                <form method="post" action="save.php" enctype='multipart/form-data'>

                                    <input name="cat" type="hidden" value="users">
                                    <input name="id" type="hidden" value="<?= $id ?>">
                                    <input name="act" type="hidden" value="<?= $act ?>">

                                    <div class="row">

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">Username:</label>
                                            <input class="form-control" type="text" name="username"
                                                value="<?= $users['username'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">TOKEN:</label>
                                            <input class="form-control" type="text" name="token"
                                                value="<?= $users['token'] ?>" />

                                        </div>
                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">ATK:</label>
                                            <input class="form-control" type="text" name="atk"
                                                value="<?= $users['atk'] ?>" />

                                        </div>
                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">SALDO:</label>
                                            <input class="form-control" type="text" name="saldo"
                                                value="<?= $users['saldo'] ?>" />

                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">VALOR APOSTADO:</label>
                                            <input class="form-control" type="text" name="valorapostado"
                                                value="<?= $users['valorapostado'] ?>" />

                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">VALOR DEBITADO:</label>
                                            <input class="form-control" type="text" name="valordebitado"
                                                value="<?= $users['valordebitado'] ?>" />

                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">Valor ganho:</label>
                                            <input class="form-control" type="text" name="valorganho"
                                                value="<?= $users['valorganho'] ?>" />

                                        </div>
                                        <div class="mb-3 col-md-2 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">RTP:</label>
                                            <input class="form-control" type="text" name="rtp"
                                                value="<?= $users['rtp'] ?>" />

                                        </div>
                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">MODO INFLUENCER COLOCAR NUMERO
                                                true PARA ATIVAR E false para desativar:</label>
                                            <input class="form-control" type="text" name="isinfluencer"
                                                value="<?= $users['is_influencer'] ?>" />

                                        </div>

                                        <div class="mb-3 col-md-2 fv-plugins-icon-container">
                                            <label for="preco_csp" class="col-form-label">AGENTE ID:</label>
                                            <input class="form-control" type="text" name="agentid"
                                                value="<?= $users['agentid'] ?>" />

                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <hr>
                                        <button type="submit" class="btn btn-label-primary me-2">Salva
                                            Alterações</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
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