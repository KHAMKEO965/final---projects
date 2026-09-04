<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Noto Sans Lao', sans-serif; background-color: #f4f6f9; }
    .sidebar { min-height: 100vh; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
    .nav-link-custom { color: #94a3b8; border-radius: 8px; transition: all 0.3s ease; padding: 10px 15px; display: flex; align-items: center; gap: 12px; text-decoration: none; font-weight: 500; margin-bottom: 4px; }
    .nav-link-custom:hover { background-color: rgba(255,255,255,0.08); color: #38bdf8; transform: translateX(4px); }
    .nav-link-custom.active-page { background: linear-gradient(90deg, #0284c7 0%, #0369a1 100%); color: #ffffff !important; box-shadow: 0 4px 12px rgba(2,132,199,0.3); }
    .brand-section { background: rgba(0,0,0,0.2); padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .user-panel { background: rgba(255,255,255,0.03); border-radius: 12px; padding: 12px; border: 1px solid rgba(255,255,255,0.05); }
    .main-content { margin-left: 16.66667%; padding: 30px; }
    .menu-group-title {
        color: #64748b;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 15px 6px 15px;
        font-weight: 700;
        opacity: 0.7;
    }
    .menu-group-title i {
        margin-right: 8px;
        font-size: 0.5rem;
        vertical-align: middle;
    }
    @media (max-width: 768px) { .sidebar { position: relative !important; min-height: auto; width: 100% !important; } .main-content { margin-left: 0 !important; padding: 15px; } }
</style>

<div class="col-md-3 col-lg-2 p-0 sidebar position-fixed top-0 start-0 d-md-block collapse" id="sidebarMenu">
    <div class="brand-section text-center mb-3">
        <h4 class="text-white fw-bold m-0 tracking-wide"><i class="fa-solid fa-plane-departure text-info me-2"></i>LAO AIR CARGO</h4>
        <span class="badge bg-info bg-opacity-20 text-info mt-2 px-3 py-1 rounded-pill small">v2.0 Premium UI</span>
    </div>
    
    <div class="px-3">
        <div class="user-panel text-center mb-4 mt-2">
            <div class="avatar bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2 shadow" style="width: 45px; height: 45px;">
                <i class="fa-solid fa-user-shield fs-5"></i>
            </div>
            <div class="text-white fw-semibold small"><?php echo $_SESSION['username'] ?? 'Administrator'; ?></div>
            <span class="text-success small" style="font-size: 0.75rem;"><i class="fa-solid fa-circle me-1 animate-pulse"></i> Online</span>
        </div>

        <nav class="nav flex-column">
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            
            // ຈັດກຸ່ມເມນູແບບມີຫົວຂໍ້ກຸ່ມ
            $menu_groups = [
                'ພາບລວມ' => [
                    'index.php' => ['ໜ້າຫຼັກ', 'fa-solid fa-chart-pie']
                ],
                'ຈັດການຂໍ້ມູນພື້ນຖານ' => [
                    'customer.php' => ['ລູກຄ້າ', 'fa-solid fa-users'],
                    'airline.php' => ['ສາຍການບິນ', 'fa-solid fa-plane'],
                    'product.php' => ['ສິນຄ້າ', 'fa-solid fa-box-open'],
                    'package_type.php' => ['ປະເພດພັດສະດຸ', 'fa-solid fa-boxes-stacked']
                ],
                'ບັນທຶກຂໍ້ມູນການເຄື່ອນໄຫວ' => [
                    'flight.php' => ['ຖ້ຽວບິນ', 'fa-solid fa-ticket'],
                    'shipment.php' => ['ການຂົນສົ່ງ', 'fa-solid fa-truck-ramp-box'],
                    'payment.php' => ['ການຊຳລະເງິນ', 'fa-solid fa-wallet'],
                    'schedule.php' => ['ຕາຕະລາງບິນ', 'fa-solid fa-calendar-days'],
                    
                    'document.php' => ['ເອກະສານ', 'fa-solid fa-folder-open']
                ],
                'ລາຍງານ' => [
                    'reports.php' => ['ລາຍງານທັງໝົດ', 'fa-solid fa-square-poll-vertical']
                ]
            ];

            foreach ($menu_groups as $group_title => $items) {
                echo '<div class="menu-group-title"><i class="fa-regular fa-circle"></i> ' . $group_title . '</div>';
                foreach ($items as $url => $data) {
                    $active = ($current_page == $url) ? 'active-page' : '';
                    echo "<a class='nav-link-custom $active' href='$url'><i class='{$data[1]} width-20 text-center'></i> <span>{$data[0]}</span></a>";
                }
            }
            ?>
            <hr class="text-secondary my-3">
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 rounded-3 mb-4 py-2" onclick="return confirm('ຢືນຢັນການອອກຈາກລະບົບ?')">
                <i class="fa-solid fa-power-off me-2"></i> ອອກຈາກລະບົບ
            </a>
        </nav>
    </div>
</div>

<nav class="navbar navbar-dark bg-dark d-md-none p-3 shadow-sm">
    <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-plane-departure text-info me-2"></i>LAO AIR CARGO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>