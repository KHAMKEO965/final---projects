<?php 
session_start();

// ຕັ້ງເຂດເວລາໃຫ້ເປັນ ອາຊີ/ວຽງຈັນ (UTC+7)
date_default_timezone_set('Asia/Vientiane');

include 'db.php';

// ດຶງຂໍ້ມູນສະຖິຕິຕ່າງໆ
$count_shipment = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM shipment"))['total'];
$count_customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM customer"))['total'];
$total_income = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM payment"))['total'] ?? 0;
$count_flights = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM flight"))['total'];
$count_product_types = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM product"))['total'];

// ຮູບແບບວັນທີ ແລະ ເວລາ
$current_date = date('d-m-Y');
$current_time = date('H:i:s');
$current_datetime = date('d-m-Y H:i:s');
$day_of_week = date('l');
$day_in_lao = [
    'Monday' => 'ວັນຈັນ',
    'Tuesday' => 'ວັນອັງຄານ',
    'Wednesday' => 'ວັນພຸດ',
    'Thursday' => 'ວັນພະຫັດ',
    'Friday' => 'ວັນສຸກ',
    'Saturday' => 'ວັນເສົາ',
    'Sunday' => 'ວັນອາທິດ'
];
$current_day_lao = $day_in_lao[$day_of_week] ?? $day_of_week;

// ຮູບແບບເດືອນພາສາລາວ
$month_in_lao = [
    '01' => 'ມັງກອນ',
    '02' => 'ກຸມພາ',
    '03' => 'ມີນາ',
    '04' => 'ເມສາ',
    '05' => 'ພຶດສະພາ',
    '06' => 'ມິຖຸນາ',
    '07' => 'ກໍລະກົດ',
    '08' => 'ສິງຫາ',
    '09' => 'ກັນຍາ',
    '10' => 'ຕຸລາ',
    '11' => 'ພະຈິກ',
    '12' => 'ທັນວາ'
];
$month_num = date('m');
$current_month_lao = $month_in_lao[$month_num] ?? $month_num;
$current_year = date('Y');
$current_day_num = date('d');

// ສ້າງວັນທີເປັນພາສາລາວ
$lao_date = "ວັນທີ {$current_day_num} ເດືອນ {$current_month_lao} ປີ {$current_year}";
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index - Lao Air Cargo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
   <style>
        /* Card Hover Effect */
        .card-hover {
            transition: all 0.3s ease;
            cursor: default;
            position: relative;
            overflow: hidden;
        }
        .card-hover::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.5s ease;
        }
        .card-hover:hover::before {
            transform: scale(2);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
        }
        .card-hover .bg-white {
            transition: all 0.3s ease;
        }
        .card-hover:hover .bg-white {
            transform: scale(1.05) rotate(-5deg);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .card-hover i.fs-3 {
            transition: all 0.3s ease;
        }
        .card-hover:hover i.fs-3 {
            transform: scale(1.1);
        }
        .card-hover h3 {
            transition: all 0.3s ease;
        }
        .card-hover:hover h3 {
            transform: scale(1.02);
        }

        /* Time Display */
        .time-display {
            background: rgba(255,255,255,0.9);
            padding: 8px 18px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            text-align: right;
            min-width: 180px;
        }
        .time-display .day {
            font-weight: 600;
            color: #2d3436;
            font-size: 14px;
        }
        .time-display .date {
            font-size: 13px;
            color: #636e72;
        }
        .time-display .time {
            font-size: 18px;
            font-weight: 700;
            color: #0984e3;
            font-variant-numeric: tabular-nums;
        }
        .time-display .time-seconds {
            font-size: 14px;
            color: #b2bec3;
            font-weight: 400;
        }

        /* Dashboard Header Icon */
        .dashboard-header-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 14px;
            color: white;
            font-size: 24px;
            margin-right: 12px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }
        .dashboard-header-icon:hover {
            transform: scale(1.05) rotate(-5deg);
            box-shadow: 0 6px 25px rgba(59, 130, 246, 0.4);
        }
        .dashboard-title-wrapper {
            display: flex;
            align-items: center;
        }
        .dashboard-title-wrapper h2 {
            font-weight: 700;
            margin: 0;
            background: linear-gradient(135deg, #1e293b, #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .dashboard-title-wrapper .subtitle {
            -webkit-text-fill-color: #64748b;
            font-size: 14px;
            margin-top: 2px;
        }

        /* Notification Styles */
        .notification-badge {
            position: relative;
            display: inline-block;
        }
        .notification-badge .badge-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: bold;
            border: 2px solid white;
            animation: pulse-badge 2s ease-in-out infinite;
        }
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .notification-dropdown {
            min-width: 380px;
            max-height: 450px;
            overflow-y: auto;
            padding: 0;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border: none;
        }
        .notification-dropdown .dropdown-header {
            padding: 15px 20px;
            background: #f8f9fa;
            border-radius: 12px 12px 0 0;
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .notification-item {
            padding: 12px 20px;
            border-bottom: 1px solid #f1f3f5;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: #212529;
            display: flex;
            align-items: start;
            gap: 12px;
        }
        .notification-item:hover {
            background: #f8f9fa;
        }
        .notification-item .noti-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }
        .notification-item .noti-icon.danger { background: #fee; color: #dc3545; }
        .notification-item .noti-icon.warning { background: #fff3cd; color: #ffc107; }
        .notification-item .noti-icon.info { background: #cff4fc; color: #0dcaf0; }
        .notification-item .noti-icon.success { background: #d1e7dd; color: #198754; }
        .notification-item .noti-content { flex: 1; }
        .notification-item .noti-title { font-weight: 600; font-size: 14px; margin-bottom: 2px; }
        .notification-item .noti-message { font-size: 13px; color: #6c757d; }
        .notification-item .noti-time { font-size: 11px; color: #adb5bd; margin-top: 4px; }
        .notification-item .noti-link { font-size: 12px; color: #0d6efd; text-decoration: none; }
        .notification-item .noti-link:hover { text-decoration: underline; }
        .notification-empty {
            padding: 40px 20px;
            text-align: center;
            color: #6c757d;
        }
        .notification-empty i { font-size: 48px; margin-bottom: 15px; opacity: 0.3; }
        .notification-dropdown::-webkit-scrollbar {
            width: 5px;
        }
        .notification-dropdown::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .notification-dropdown::-webkit-scrollbar-thumb {
            background: #c1c7cd;
            border-radius: 10px;
        }
        .notification-dropdown::-webkit-scrollbar-thumb:hover {
            background: #a8b0b8;
        }

        @media (max-width: 768px) {
            .time-display {
                text-align: center;
                min-width: unset;
                width: 100%;
            }
            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: stretch !important;
                gap: 10px;
            }
            .notification-dropdown {
                min-width: 300px;
                right: -50px !important;
            }
            .dashboard-title-wrapper h2 {
                font-size: 20px;
            }
            .dashboard-header-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'navbar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 main-content px-md-4 py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom flex-wrap gap-2">
                <div class="dashboard-title-wrapper">
                    <div class="dashboard-header-icon">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>
                    <div>
                        <h2>ແຜງຄວບຄຸມລະບົບ</h2>
                        <p class="text-muted m-0 subtitle">
                            <i class="fa-regular fa-circle-check me-1" style="color: #10b981;"></i>
                            ພາບລວມສະຖິຕິ ແລະ ຂໍ້ມູນຂົນສົ່ງທາງອາກາດທັງໝົດ
                        </p>
                    </div>
                </div>
                
                <!-- Time Display -->
                <div class="time-display">
                    <div class="day">
                        <i class="fa-regular fa-calendar me-1"></i>
                        <?php echo $current_day_lao; ?>
                    </div>
                    <div class="date">
                        <?php echo $lao_date; ?>
                    </div>
                    <div class="time" id="liveTime">
                        <i class="fa-regular fa-clock me-1"></i>
                        <?php echo $current_time; ?>
                        <span class="time-seconds" id="liveSeconds"></span>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-5 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
                
                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 text-white h-100 card-hover" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-bold small mb-2">ຈຳນວນການສົ່ງ</h6>
                                <h3 class="fw-bold m-0"><?php echo number_format($count_shipment); ?></h3>
                                <small class="text-white-50">ລາຍການ</small>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-3"><i class="fa-solid fa-boxes-stacked fs-3"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 text-white h-100 card-hover" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-bold small mb-2">ລາຍຮັບທັງໝົດ</h6>
                                <h3 class="fw-bold m-0"><?php echo number_format($total_income, 0); ?></h3>
                                <small class="text-white-50">ກີບ</small>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-3"><i class="fa-solid fa-hand-holding-dollar fs-3"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 text-white h-100 card-hover" style="background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-bold small mb-2">ລູກຄ້າທັງໝົດ</h6>
                                <h3 class="fw-bold m-0"><?php echo number_format($count_customer); ?></h3>
                                <small class="text-white-50">ຄົນ</small>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-3"><i class="fa-solid fa-users fs-3"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 text-white h-100 card-hover" style="background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-bold small mb-2">ຖ້ຽວບິນທັງໝົດ</h6>
                                <h3 class="fw-bold m-0"><?php echo number_format($count_flights); ?></h3>
                                <small class="text-white-50">ຖ້ຽວ</small>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-3"><i class="fa-solid fa-plane fs-3"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 text-white h-100 card-hover" style="background: linear-gradient(135deg, #a855f7 0%, #6b21a8 100%);">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-bold small mb-2">ປະເພດພັດສະດຸ</h6>
                                <h3 class="fw-bold m-0"><?php echo number_format($count_product_types); ?></h3>
                                <small class="text-white-50">ປະເພດ</small>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-3"><i class="fa-solid fa-cubes fs-3"></i></div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Additional Info -->
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="fa-solid fa-server text-primary fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small text-uppercase fw-bold mb-0">ເວລາລະບົບ</h6>
                                <span class="fw-bold" id="systemTime"><?php echo $current_datetime; ?></span>
                                <br>
                                <small class="text-muted">ເຂດເວລາ: Asia/Vientiane (UTC+7)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="fa-solid fa-circle-check text-success fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small text-uppercase fw-bold mb-0">ສະຖານະລະບົບ</h6>
                                <span class="fw-bold text-success">ປົກກະຕິ</span>
                                <br>
                                <small class="text-muted">ກຳລັງເຮັດວຽກປົກກະຕິ</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Update time every second
                function updateClock() {
                    const now = new Date();
                    
                    // Format time with leading zeros
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    const timeStr = hours + ':' + minutes + ':' + seconds;
                    
                    // Update main time display
                    const timeDisplay = document.getElementById('liveTime');
                    if (timeDisplay) {
                        timeDisplay.innerHTML = `
                            <i class="fa-regular fa-clock me-1"></i>
                            ${hours}:${minutes}
                            <span class="time-seconds">${seconds}</span>
                        `;
                    }
                    
                    // Update system time
                    const day = String(now.getDate()).padStart(2, '0');
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const year = now.getFullYear();
                    const dateStr = day + '-' + month + '-' + year + ' ' + timeStr;
                    
                    const systemTime = document.getElementById('systemTime');
                    if (systemTime) {
                        systemTime.textContent = dateStr;
                    }
                }

                // Update immediately and then every second
                updateClock();
                setInterval(updateClock, 1000);
            </script>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>