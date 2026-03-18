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
                                    <h5 class="m-0 me-2">Lista de Users</h5>
                                    <div class="card-title-elements ms-auto">
                                        <a href="edit-users.php?act=add" class="btn btn-icon btn-primary">
                                            <span class="tf-icon bx bx-plus"></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <div class="table-responsive text-nowrap">
                                        <div class="row">
                                            <p>Total de users <?php echo counting("users", "id"); ?> users.</p>

                                            <table id="sorted" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Username</th>
                                                        <th>TOKEN</th>
                                                        <th>ATK</th>
                                                        <th>SALDO</th>
                                                        <th>VALOR APOSTADO</th>
                                                        <th>VALOR DEBITADO</th>
                                                        <th>VALOR GANHO</th>
                                                        <th>RTP</th>
                                                        <th>INFLUENCER</th>
                                                        <th>AGENTID</th>
                                                        <th class="not">Action</th>
                                                    </tr>
                                                </thead>
                                                <?php
												$users = getAll("users");
												if ($users) foreach ($users as $userss) :
												?>
                                                <tr>
                                                    <td><?php echo $userss['id'] ?></td>
                                                    <td><?php echo $userss['username'] ?></td>
                                                    <td><?php echo $userss['token'] ?></td>
                                                    <td><?php echo $userss['atk'] ?></td>
                                                    <td><?php echo $userss['saldo'] ?></td>
                                                    <td><?php echo $userss['valorapostado'] ?></td>
                                                    <td><?php echo $userss['valordebitado'] ?></td>
                                                    <td><?php echo $userss['valorganho'] ?></td>
                                                    <td><?php echo $userss['rtp'] ?></td>
                                                    <td><?php echo $userss['is_influencer'] ?></td>
                                                    <td><?php echo $userss['agentid'] ?></td>
                                                    <td>
                                                        <div class="text-nowrap">
                                                            <a href="edit-users.php?act=edit&id=<?php echo $userss['id'] ?>"
                                                                class="btn btn-icon btn-label-info">
                                                                <span class="tf-icons bx bx-message-square-edit"></span>
                                                            </a>
                                                            <a href="save.php?act=delete&id=<?php echo $userss['id'] ?>&cat=users"
                                                                onclick="return navConfirm(this.href);"
                                                                class="btn btn-icon btn-label-danger">
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