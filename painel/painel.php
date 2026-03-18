<?php
error_reporting(0);
ini_set('display_errors', 1);
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
                        Andre
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