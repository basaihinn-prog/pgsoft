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
									$users = getById("agents", $id);
								}
								?>

                                <form method="post" action="save-agents.php" enctype='multipart/form-data'>





                                    <input name="cat" type="hidden" value="agents">
                                    <input name="id" type="hidden" value="<?= $id ?>">
                                    <input name="act" type="hidden" value="<?= $act ?>">





                                    <div class="row">
                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="agentcode" class="col-form-label">agentCode:</label>
                                            <input class="form-control" type="text" name="agentcode"
                                                value="<?= $users['agentcode'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="senha" class="col-form-label">senha:</label>
                                            <input class="form-control" type="text" name="senha"
                                                value="<?= $users['senha'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="saldo" class="col-form-label">saldo:</label>
                                            <input class="form-control" type="text" name="saldo"
                                                value="<?= $users['saldo'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="agentToken" class="col-form-label">agentToken:</label>
                                            <input class="form-control" type="text" name="agentToken"
                                                value="<?= $users['agentToken'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="secretKey" class="col-form-label">secretKey:</label>
                                            <input class="form-control" type="text" name="secretKey"
                                                value="<?= $users['secretKey'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="probganho" class="col-form-label">probganho:</label>
                                            <input class="form-control" type="text" name="probganho"
                                                value="<?= $users['probganho'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="probbonus" class="col-form-label">probbonus:</label>
                                            <input class="form-control" type="text" name="probbonus"
                                                value="<?= $users['probbonus'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="probganhortp" class="col-form-label">probganhortp:</label>
                                            <input class="form-control" type="text" name="probganhortp"
                                                value="<?= $users['probganhortp'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="probganhoinfluencer"
                                                class="col-form-label">probganhoinfluencer:</label>
                                            <input class="form-control" type="text" name="probganhoinfluencer"
                                                value="<?= $users['probganhoinfluencer'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="probbonusinfluencer"
                                                class="col-form-label">probbonusinfluencer:</label>
                                            <input class="form-control" type="text" name="probbonusinfluencer"
                                                value="<?= $users['probbonusinfluencer'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="probganhoaposta" class="col-form-label">probganhoaposta:</label>
                                            <input class="form-control" type="text" name="probganhoaposta"
                                                value="<?= $users['probganhoaposta'] ?>" />
                                        </div>

                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="probganhosaldo" class="col-form-label">probganhosaldo:</label>
                                            <input class="form-control" type="text" name="probganhosaldo"
                                                value="<?= $users['probganhosaldo'] ?>" />
                                        </div>
                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="limitadorchicky" class="col-form-label">Limitador do
                                                Chicky(JOGADOR PERDE SE O GANHO FOR ULTRAPASAR ):</label>
                                            <input class="form-control" type="text" name="limitadorchicky"
                                                value="<?= $users['limitadorchicky'] ?>" />
                                        </div>
                                        <div class="mb-3 col-md-4 fv-plugins-icon-container">
                                            <label for="callbackurl" class="col-form-label">callbackurl:</label>
                                            <input class="form-control" type="text" name="callbackurl"
                                                value="<?= $users['callbackurl'] ?>" />
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