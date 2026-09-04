<!-- Sidebar -->
<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar vh-100 p-3 position-fixed">
    <div class="text-center mb-4">
        <h4 class="text-white">
            <i class="bi bi-airplane-fill text-primary"></i> AirCargo
        </h4>
        <p class="text-secondary small">ລະບົບຂົນສົ່ງທາງອາກາດ</p>
        <hr class="text-secondary">
    </div>
    
    <ul class="nav flex-column">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['PHP_SELF'], 'modules') === false ? 'active' : ''; ?>" 
               href="../index.php">
                <i class="bi bi-speedometer2"></i> ໜ້າຫຼັກ
            </a>
        </li>

        <!-- Customer -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'customer') !== false ? 'active' : ''; ?>" 
               href="../modules/customer/index.php">
                <i class="bi bi-people"></i> ລູກຄ້າ
            </a>
        </li>

        <!-- Airline -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'airline') !== false ? 'active' : ''; ?>" 
               href="../modules/airline/index.php">
                <i class="bi bi-building"></i> ສາຍການບິນ
            </a>
        </li>

        <!-- Product -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'product') !== false ? 'active' : ''; ?>" 
               href="../modules/product/index.php">
                <i class="bi bi-box"></i> ສິນຄ້າ
            </a>
        </li>

        <!-- Flight -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'flight') !== false ? 'active' : ''; ?>" 
               href="../modules/flight/index.php">
                <i class="bi bi-airplane"></i> ຖ້ຽວບິນ
            </a>
        </li>

        <!-- Shipment -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'shipment') !== false ? 'active' : ''; ?>" 
               href="../modules/shipment/index.php">
                <i class="bi bi-truck"></i> ການຂົນສົ່ງ
            </a>
        </li>

        <!-- Payment -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'payment') !== false ? 'active' : ''; ?>" 
               href="../modules/payment/index.php">
                <i class="bi bi-credit-card"></i> ການຊຳລະ
            </a>
        </li>

        <!-- Schedule -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'schedule') !== false ? 'active' : ''; ?>" 
               href="../modules/schedule/index.php">
                <i class="bi bi-calendar"></i> ຕາຕະລາງ
            </a>
        </li>

        <!-- Document -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'document') !== false ? 'active' : ''; ?>" 
               href="../modules/document/index.php">
                <i class="bi bi-file-earmark"></i> ເອກະສານ
            </a>
        </li>

        <!-- Divider -->
        <li class="nav-item mt-3">
            <hr class="text-secondary">
        </li>

        <!-- Reports Dropdown -->
        <li class="nav-item">
            <a class="nav-link text-white" data-bs-toggle="collapse" href="#reportsMenu" role="button" aria-expanded="false" aria-controls="reportsMenu">
                <i class="bi bi-file-earmark-text"></i> ລາຍງານ <i class="bi bi-chevron-down float-end"></i>
            </a>
            <div class="collapse <?php echo strpos($_SERVER['PHP_SELF'], 'reports') !== false ? 'show' : ''; ?>" id="reportsMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link text-secondary small <?php echo strpos($_SERVER['PHP_SELF'], 'shipment_report') !== false ? 'active' : ''; ?>" 
                           href="../reports/shipment_report.php">
                            <i class="bi bi-arrow-right"></i> ລາຍງານການຂົນສົ່ງ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary small <?php echo strpos($_SERVER['PHP_SELF'], 'payment_report') !== false ? 'active' : ''; ?>" 
                           href="../reports/payment_report.php">
                            <i class="bi bi-arrow-right"></i> ລາຍງານການຊຳລະ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary small <?php echo strpos($_SERVER['PHP_SELF'], 'status_report') !== false ? 'active' : ''; ?>" 
                           href="../reports/status_report.php">
                            <i class="bi bi-arrow-right"></i> ລາຍງານສະຖານະ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary small <?php echo strpos($_SERVER['PHP_SELF'], 'statistics_report') !== false ? 'active' : ''; ?>" 
                           href="../reports/statistics_report.php">
                            <i class="bi bi-arrow-right"></i> ລາຍງານສະຖິຕິ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary small <?php echo strpos($_SERVER['PHP_SELF'], 'invoice') !== false ? 'active' : ''; ?>" 
                           href="../reports/invoice.php">
                            <i class="bi bi-arrow-right"></i> ໃບບິນການສົ່ງເຄື່ອງ
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Divider -->
        <li class="nav-item mt-3">
            <hr class="text-secondary">
        </li>

        <!-- User -->
        <li class="nav-item">
            <a class="nav-link text-white" href="#">
                <i class="bi bi-person-circle"></i> ຜູ້ໃຊ້: <span class="text-primary">admin</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="#" onclick="return confirm('ທ່ານຕ້ອງການອອກຈາກລະບົບ?')">
                <i class="bi bi-box-arrow-right"></i> ອອກຈາກລະບົບ
            </a>
        </li>
    </ul>

    <!-- Version -->
    <div class="position-absolute bottom-0 start-0 w-100 p-3">
        <hr class="text-secondary">
        <p class="text-secondary small text-center mb-0">
            <i class="bi bi-code-square"></i> Version 1.0
        </p>
    </div>
</nav>

