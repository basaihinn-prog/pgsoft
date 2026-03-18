<!doctype html>
<html>
<?php
error_reporting(0);
ini_set('display_errors', 1);
?>

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
                                    <h5 class="m-0 me-2">Lista de Agents</h5>
                                    <div class="card-title-elements ms-auto">
                                        <div class="card-title-elements ms-auto">
                                            <a href="edit-agents.php?act=add" class="btn btn-icon btn-primary">
                                                <span class="tf-icon bx bx-plus"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-datatable table-responsive">
                                <div class="table-responsive text-nowrap">
                                    <table id="sorted" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>CODE</th>
                                                <th>SENHA</th>
                                                <th>SALDO</th>
                                                <th>TOKEN</th>
                                                <th>SECRET KEY</th>
                                                <th>P. GANHO</th>
                                                <th>PG BONUS</th>
                                                <th>P. GANHORTP</th>
                                                <th>PG INFLENCER</th>
                                                <th>PB INFLUENCER</th>
                                                <th>PG APOSTA</th>
                                                <th>PG SALDO</th>
                                                <th>LIMITADOR CHICKY</th>
                                                <th>CALLBACK URL</th>
                                                <th class="not">Action</th>
                                            </tr>
                                        </thead>
                                        <?php
									$users = getAG("agents");

									if ($users) foreach ($users as $userss) :
									?>
                                        <tr>
                                            <td><?php echo $userss['id'] ?></td>
                                            <td><?php echo $userss['agentcode'] ?></td>
                                            <td><?php echo $userss['senha'] ?></td>
                                            <td><?php echo $userss['saldo'] ?></td>
                                            <td><?php echo $userss['agentToken'] ?></td>
                                            <td><?php echo $userss['secretKey'] ?></td>
                                            <td><?php echo $userss['probganho'] ?></td>
                                            <td><?php echo $userss['probbonus'] ?></td>
                                            <td><?php echo $userss['probganhortp'] ?></td>
                                            <td><?php echo $userss['probganhoinfluencer'] ?></td>
                                            <td><?php echo $userss['probbonusinfluencer'] ?></td>
                                            <td><?php echo $userss['probganhoaposta'] ?></td>
                                            <td><?php echo $userss['probganhosaldo'] ?></td>
                                            <td><?php echo $userss['limitadorchicky'] ?></td>
                                            <td><?php echo $userss['callbackurl'] ?></td>
                                            <td>
                                                <div class="text-nowrap">
                                                    <a href="edit-agents.php?act=edit&id=<?php echo $userss['id'] ?>"
                                                        class="btn btn-icon btn-label-info">
                                                        <span class="tf-icons bx bx-message-square-edit"></span>
                                                    </a>
                                                    <a href="#" class="btn btn-icon btn-label-danger">
                                                        <span class="tf-icons bx bx-trash"></span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </table>
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