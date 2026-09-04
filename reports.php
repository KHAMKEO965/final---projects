<?php 
include 'db.php'; 

// ຂໍ້ມູນລາຍຮັບຕາມເດືອນ
$payments = mysqli_query($conn, "SELECT SUM(amount) as amt, DATE_FORMAT(payment_date, '%M %Y') as mth, DATE_FORMAT(payment_date, '%m') as m, DATE_FORMAT(payment_date, '%Y') as y FROM payment GROUP BY mth ORDER BY y DESC, m DESC");

// ຂໍ້ມູນສະຖານະການຂົນສົ່ງ
$ship_stats = mysqli_query($conn, "SELECT status, COUNT(*) as count FROM shipment GROUP BY status");

// ຂໍ້ມູນລວມໄວ
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM payment"))['total'] ?? 0;
$total_shipments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM shipment"))['total'] ?? 0;
$pending_shipments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM shipment WHERE status = 'ກຳລັງຂົນສົ່ງ' OR status = 'ລໍຖ້າ'"))['total'] ?? 0;

// ເກັບຂໍ້ມູນສຳລັບ Chart.js
$months = [];
$amounts = [];
while($p = mysqli_fetch_assoc($payments)){
    $months[] = $p['mth'];
    $amounts[] = $p['amt'];
}
// ຣີເຊັດ pointer ກັບຄືນ
mysqli_data_seek($payments, 0);

// ຂໍ້ມູນສຳລັບສະຖານະ (Pie Chart)
$status_labels = [];
$status_counts = [];
while($ss = mysqli_fetch_assoc($ship_stats)){
    $status_labels[] = $ss['status'];
    $status_counts[] = $ss['count'];
}
mysqli_data_seek($ship_stats, 0);
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລາຍງານສະຖິຕິ ແລະ ການວິເຄາະ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Phetsarath OT', 'Noto Sans Lao', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            min-height: 100vh;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            width: 270px;
            background: #1a1a2e;
            color: #fff;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 25px 20px;
            background: #16213e;
            border-bottom: 1px solid #2a3a5e;
        }

        .sidebar-brand h2 {
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand h2 i {
            color: #4fc3f7;
            font-size: 28px;
        }

        .sidebar-brand small {
            display: block;
            font-size: 12px;
            color: #8899bb;
            margin-top: 4px;
            padding-left: 44px;
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .menu-group {
            margin-bottom: 8px;
        }

        .menu-group-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #667799;
            padding: 10px 20px 6px;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 20px;
            color: #b0c0dd;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-size: 14px;
        }

        .menu-item:hover {
            background: #2a2a4e;
            color: #ffffff;
            border-left-color: #4fc3f7;
        }

        .menu-item.active {
            background: #2a2a4e;
            color: #ffffff;
            border-left-color: #4fc3f7;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: #667799;
        }

        .menu-item.active i,
        .menu-item:hover i {
            color: #4fc3f7;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #2a3a5e;
            font-size: 12px;
            color: #667799;
            text-align: center;
        }

        .sidebar-footer a {
            color: #667799;
            text-decoration: none;
            display: inline-block;
            margin-top: 6px;
        }

        .sidebar-footer a:hover {
            color: #4fc3f7;
        }

        /* ===== Main Content ===== */
        .main-content {
            margin-left: 270px;
            flex: 1;
            padding: 25px 30px;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            font-size: 26px;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header h1 i {
            color: #4fc3f7;
        }

        .page-header .subtitle {
            font-size: 14px;
            color: #8899bb;
            font-weight: normal;
            margin-left: 10px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-success {
            background: #66bb6a;
            color: #fff;
        }

        .btn-success:hover {
            background: #4caf50;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #e0e4ec;
            color: #555;
        }

        .btn-outline:hover {
            border-color: #4fc3f7;
            color: #4fc3f7;
        }

        .btn-primary {
            background: #4fc3f7;
            color: #1a1a2e;
        }

        .btn-primary:hover {
            background: #3ba8d6;
            transform: translateY(-1px);
        }

        /* ===== Stats Cards ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 18px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 4px solid #4fc3f7;
        }

        .stat-card .stat-label {
            font-size: 12px;
            color: #8899bb;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .stat-card .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
            margin-top: 4px;
        }

        .stat-card .stat-sub {
            font-size: 12px;
            color: #8899bb;
            margin-top: 4px;
        }

        .stat-card.delivered { border-left-color: #66bb6a; }
        .stat-card.in-transit { border-left-color: #ffa726; }
        .stat-card.pending { border-left-color: #ef5350; }
        .stat-card.revenue { border-left-color: #42a5f5; }
        .stat-card.customers { border-left-color: #ab47bc; }
        .stat-card.weight { border-left-color: #26a69a; }

        /* ===== Report Sections ===== */
        .report-section {
            background: #fff;
            border-radius: 10px;
            padding: 20px 24px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .report-section .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f2f5;
            flex-wrap: wrap;
            gap: 10px;
        }

        .report-section .section-header h3 {
            font-size: 18px;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .report-section .section-header h3 i {
            color: #4fc3f7;
        }

        .report-section .section-header .count-badge {
            background: #f0f2f5;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 13px;
            color: #555;
        }

        /* ===== Tables ===== */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        table th {
            background: #f8f9fc;
            color: #555;
            font-weight: 600;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 2px solid #e8ecf4;
            white-space: nowrap;
        }

        table td {
            padding: 9px 12px;
            border-bottom: 1px solid #eef1f8;
            color: #333;
        }

        table tr:hover td {
            background: #f8f9fc;
        }

        .status-badge {
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge.delivered { background: #e8f5e9; color: #2e7d32; }
        .status-badge.in_transit { background: #fff3e0; color: #e65100; }
        .status-badge.pending { background: #ffebee; color: #c62828; }
        .status-badge.cancelled { background: #eceff1; color: #546e7a; }
        .status-badge.completed { background: #e8f5e9; color: #2e7d32; }
        .status-badge.paid { background: #e8f5e9; color: #2e7d32; }
        .status-badge.unpaid { background: #ffebee; color: #c62828; }
        .status-badge.partial { background: #fff3e0; color: #e65100; }

        .text-muted { color: #8899bb; }
        .text-success { color: #2e7d32; }
        .text-warning { color: #e65100; }
        .text-danger { color: #c62828; }
        .text-center { text-align: center; }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #8899bb;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        /* ===== Stats Grid Small ===== */
        .stats-mini-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .stats-mini-item {
            background: #f8f9fc;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }

        .stats-mini-item .mini-label {
            font-size: 11px;
            color: #8899bb;
        }

        .stats-mini-item .mini-value {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
        }

        /* ===== Responsive ===== */
        .menu-toggle {
            display: none;
            background: #1a1a2e;
            color: #fff;
            border: none;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 20px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .page-header h1 {
                font-size: 20px;
            }
            
            .page-header h1 .subtitle {
                display: block;
                margin-left: 0;
                font-size: 12px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            table {
                font-size: 11px;
            }
            table th, table td {
                padding: 6px 8px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .stats-mini-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: #1a1a2e; }
        .sidebar::-webkit-scrollbar-thumb { background: #4fc3f7; border-radius: 4px; }

        .highlight-row {
            background: #f0f7ff !important;
        }
    </style>
</head>
<body>


<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Navbar (ດຶງມາໃຊ້ງານ) -->
        <?php include 'navbar.php'; ?>

        <!-- ເນື້ອຫາຫຼັກ -->
        <main class="main-content p-4">

            <!-- Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        <i class="fa-solid fa-chart-line text-primary me-2"></i>ລາຍງານສະຖິຕິ ແລະ ການວິເຄາະ
                    </h2>
                    <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i>ອັບເດດຫຼ້າສຸດ: <?= date('d/m/Y H:i') ?></small>
                </div>
                <div class="no-print d-flex gap-2">
                    <button class="btn btn-outline-secondary print-btn" onclick="window.history.back()">
                        <i class="fa-solid fa-arrow-left me-2"></i>ກັບຄືນ
                    </button>
                    <button class="btn btn-success print-btn" onclick="window.print()">
                        <i class="fa-solid fa-print me-2"></i>ພິມລາຍງານ
                    </button>
                </div>
            </div>

            <!-- ສະຖິຕິດ່ວນ (Quick Stats) -->
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                            <div>
                                <div class="label">ລາຍຮັບທັງໝົດ</div>
                                <div class="value"><?= number_format($total_revenue) ?> <small style="font-size:14px;">₭</small></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fa-solid fa-boxes"></i>
                            </div>
                            <div>
                                <div class="label">ການຂົນສົ່ງທັງໝົດ</div>
                                <div class="value"><?= number_format($total_shipments) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <div class="label">ກຳລັງດຳເນີນ</div>
                                <div class="value"><?= number_format($pending_shipments) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                            <div>
                                <div class="label">ສຳເລັດແລ້ວ</div>
                                <div class="value"><?= number_format($total_shipments - $pending_shipments) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts + Table -->
            <div class="row g-4">
                <!-- Chart ລາຍຮັບຕາມເດືອນ (Bar) -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 card-hover">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-chart-bar text-success me-2"></i>ລາຍຮັບຕາມເດືອນ
                        </h5>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart ສະຖານະການຂົນສົ່ງ (Pie) -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 card-hover">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-chart-pie text-primary me-2"></i>ສະຖານະການຂົນສົ່ງ
                        </h5>
                        <div class="chart-container" style="height:240px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ຕາຕະລາງລາຍລະອຽດ -->
            <div class="row g-4 mt-3">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 card-hover">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark m-0">
                                <i class="fa-solid fa-table me-2 text-secondary"></i>ລາຍລະອຽດລາຍຮັບຕາມເດືອນ
                            </h5>
                            <span class="badge bg-light text-secondary border"><?= mysqli_num_rows($payments) ?> ເດືອນ</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th><i class="fa-regular fa-calendar me-2"></i>ເດືອນ / ປີ</th>
                                        <th class="text-end"><i class="fa-solid fa-money-bill me-2"></i>ລາຍຮັບລວມ</th>
                                        <th class="text-center"><i class="fa-regular fa-chart-bar me-2"></i>ສະຖານະ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $has_data = false;
                                    while($p = mysqli_fetch_assoc($payments)){ 
                                        $has_data = true;
                                        $amt = $p['amt'];
                                        // ກຳນົດສີຕາມລາຍຮັບ
                                        if($amt >= 2000000) $badge = 'success';
                                        elseif($amt >= 1000000) $badge = 'primary';
                                        elseif($amt >= 500000) $badge = 'warning';
                                        else $badge = 'secondary';
                                    ?>
                                    <tr>
                                        <td><strong><?= $p['mth'] ?></strong></td>
                                        <td class="text-end fw-bold text-success"><?= number_format($amt) ?> <span style="font-size:13px;color:#6c7a8d;">₭</span></td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= $badge ?> bg-opacity-10 text-<?= $badge ?> badge-status">
                                                <?php if($amt >= 2000000): ?>
                                                    <i class="fa-solid fa-circle-check me-1"></i>ດີເດັ່ນ
                                                <?php elseif($amt >= 1000000): ?>
                                                    <i class="fa-solid fa-chart-line me-1"></i>ດີ
                                                <?php elseif($amt >= 500000): ?>
                                                    <i class="fa-solid fa-arrow-up me-1"></i>ປານກາງ
                                                <?php else: ?>
                                                    <i class="fa-solid fa-arrow-down me-1"></i>ຕໍ່າ
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php } 
                                    if(!$has_data){ ?>
                                    <tr><td colspan="3" class="text-center text-muted py-4">
                                        <i class="fa-regular fa-face-frown me-2"></i>ບໍ່ມີຂໍ້ມູນລາຍຮັບ
                                    </td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ສະຖານະການຂົນສົ່ງຫຼ້າສຸດ (ສະແດງແບບລາຍການ) -->
            <div class="row g-4 mt-2">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 card-hover">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-truck-fast text-primary me-2"></i>ສະຖານະການຂົນສົ່ງແບ່ງຕາມປະເພດ
                        </h5>
                        <div class="row g-3">
                            <?php 
                            $status_colors = [
                                'ສຳເລັດ' => 'success',
                                'ກຳລັງຂົນສົ່ງ' => 'warning',
                                'ລໍຖ້າ' => 'secondary',
                                'ຍົກເລີກ' => 'danger'
                            ];
                            while($ss = mysqli_fetch_assoc($ship_stats)){ 
                                $color = $status_colors[$ss['status']] ?? 'primary';
                            ?>
                            <div class="col-md-3 col-6">
                                <div class="p-3 bg-light rounded-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small"><?= $ss['status'] ?></div>
                                        <strong class="fs-4"><?= $ss['count'] ?></strong>
                                    </div>
                                    <span class="badge bg-<?= $color ?> bg-opacity-10 text-<?= $color ?> p-3 rounded-circle">
                                        <i class="fa-solid fa-box"></i>
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-muted small mt-4 pt-2 border-top no-print">
                <i class="fa-regular fa-copyright me-1"></i> ລາຍງານສະຖິຕິ ຂົນສົ່ງສິນຄ້າ ປີ 2026
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ========== Chart ລາຍຮັບ (Bar Chart) ==========
const ctx1 = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'ລາຍຮັບ (ກີບ)',
            data: <?= json_encode($amounts) ?>,
            backgroundColor: 'rgba(13, 110, 253, 0.7)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 2,
            borderRadius: 8,
            barThickness: 40,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y.toLocaleString() + ' ₭';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' ₭';
                    }
                }
            }
        }
    }
});

// ========== Chart ສະຖານະ (Pie Chart) ==========
const ctx2 = document.getElementById('statusChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($status_labels) ?>,
        datasets: [{
            data: <?= json_encode($status_counts) ?>,
            backgroundColor: [
                '#28a745', // ສຳເລັດ
                '#ffc107', // ກຳລັງຂົນສົ່ງ
                '#6c757d', // ລໍຖ້າ
                '#dc3545', // ຍົກເລີກ
                '#0d6efd'  // ອື່ນໆ
            ],
            borderWidth: 3,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 16,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: {
                        size: 13
                    }
                }
            }
        },
        cutout: '65%'
    }
});
</script>

</body>
</html>