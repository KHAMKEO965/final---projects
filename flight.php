<?php
session_start();

include 'db.php';

// 1. CREATE: ເພີ່ມຂໍ້ມູນຖ້ຽວບິນ
if (isset($_POST['add'])) {
    $airline_id = intval($_POST['airline_id']);
    $flight_number = mysqli_real_escape_string($conn, $_POST['flight_number']);
    $origin = mysqli_real_escape_string($conn, $_POST['origin']);
    $destination = mysqli_real_escape_string($conn, $_POST['destination']);
    $departure_time = mysqli_real_escape_string($conn, $_POST['departure_time']);
    $arrival_time = mysqli_real_escape_string($conn, $_POST['arrival_time']);
    
    mysqli_query($conn, "INSERT INTO flight (airline_id, flight_number, origin, destination, departure_time, arrival_time) VALUES ('$airline_id', '$flight_number', '$origin', '$destination', '$departure_time', '$arrival_time')");
    header("Location: flight.php"); exit();
}

// 2. UPDATE: ແກ້ໄຂຂໍ້ມູນຖ້ຽວບິນ
if (isset($_POST['update'])) {
    $id = intval($_POST['flight_id']);
    $airline_id = intval($_POST['airline_id']);
    $flight_number = mysqli_real_escape_string($conn, $_POST['flight_number']);
    $origin = mysqli_real_escape_string($conn, $_POST['origin']);
    $destination = mysqli_real_escape_string($conn, $_POST['destination']);
    $departure_time = mysqli_real_escape_string($conn, $_POST['departure_time']);
    $arrival_time = mysqli_real_escape_string($conn, $_POST['arrival_time']);
    
    mysqli_query($conn, "UPDATE flight SET airline_id='$airline_id', flight_number='$flight_number', origin='$origin', destination='$destination', departure_time='$departure_time', arrival_time='$arrival_time' WHERE flight_id=$id");
    header("Location: flight.php"); exit();
}

// 3. DELETE: ລຶບຂໍ້ມູນຖ້ຽວບິນ
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM flight WHERE flight_id = $id");
    header("Location: flight.php"); exit();
}

// ລາຍຊື່ແຂວງ ສປປ ລາວ
$provinces = [
    'ວຽງຈັນ (VTE)',
    'ຫຼວງພະບາງ (LPQ)',
    'ສະຫວັນນະເຂດ (SVK)',
    'ຈຳປາສັກ (PKZ)',
    'ອຸດົມໄຊ (ODY)',
    'ບໍ່ແກ້ວ (BOK)',
    'ຫຼວງນ້ຳທາ (LXG)',
    'ຜົ້ງສາລີ (PHO)',
    'ໄຊຍະບູລີ (XAY)',
    'ຊຽງຂວາງ (XKH)',
    'ວຽງຈັນ (VTE)',
    'ບໍລິຄຳໄຊ (BLK)',
    'ຄຳມ່ວນ (KHM)',
    'ສາລະວັນ (SAL)',
    'ເຊກອງ (SEK)',
    'ອັດຕະປື (ATP)',
    'ໄຊສົມບູນ (XSB)'
];
// ກຳຈັດຄ່າຊໍ້າກັນ
$provinces = array_unique($provinces);
sort($provinces);
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຈັດການຂໍ້ມູນຖ້ຽວບິນ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;600;700&display=swap');
        body { 
            font-family: 'Noto Sans Lao', 'Phetsarath OT', sans-serif; 
            background-color: #f4f6f9; 
        }
        .card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.04) !important;
        }
        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        .table th { 
            font-weight: 600; 
            background-color: #f8fafc !important; 
            color: #475569;
        }
        .btn-action {
            padding: 6px 14px;
            border-radius: 10px;
            font-weight: 500;
        }
        .time-badge {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 20px;
            background-color: #f1f5f9;
            color: #334155;
            display: inline-block;
        }
        .time-badge i {
            margin-right: 4px;
            color: #64748b;
        }
        .province-select {
            cursor: pointer;
        }
        .province-select option {
            padding: 8px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'navbar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 main-content py-4 px-md-4">
            
            <div class="card p-4 mb-4 bg-white">
                <h3 class="fw-bold text-dark mb-4">
                    <i class="fa-solid fa-ticket text-primary me-2"></i> ຈັດການຂໍ້ມູນຖ້ຽວບິນ
                </h3>
                
                <form method="POST" class="row g-3 p-3 bg-light rounded-4">
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ສາຍການບິນ</label>
                        <select name="airline_id" class="form-select p-2 rounded-3" required>
                            <option value="">-- ເລືອກ --</option>
                            <?php 
                            $air = mysqli_query($conn, "SELECT * FROM airline"); 
                            while($r = mysqli_fetch_assoc($air)) {
                                echo "<option value='{$r['airline_id']}'>{$r['airline_name']}</option>"; 
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ເລກຖ້ຽວບິນ</label>
                        <input type="text" name="flight_number" class="form-control p-2 rounded-3" placeholder="ຕົວຢ່າງ: QV101" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ຕົ້ນທາງ (Origin)</label>
                        <select name="origin" class="form-select p-2 rounded-3 province-select" required>
                            <option value="">-- ເລືອກແຂວງ --</option>
                            <?php foreach($provinces as $province): ?>
                                <option value="<?= $province ?>"><?= $province ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ປາຍທາງ (Destination)</label>
                        <select name="destination" class="form-select p-2 rounded-3 province-select" required>
                            <option value="">-- ເລືອກແຂວງ --</option>
                            <?php foreach($provinces as $province): ?>
                                <option value="<?= $province ?>"><?= $province ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ເວລາເຂົ້າ</label>
                        <input type="datetime-local" name="departure_time" class="form-control p-2 rounded-3" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small text-muted fw-semibold">ເວລາອອກ</label>
                        <input type="datetime-local" name="arrival_time" class="form-control p-2 rounded-3" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" name="add" class="btn btn-primary p-2 w-100 rounded-3 shadow-sm">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card p-3 bg-white">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="6%">ID</th>
                                <th>ສາຍການບິນ</th>
                                <th>ເລກຖ້ຽວບິນ</th>
                                <th>ເສັ້ນທາງບິນ</th>
                                <th width="18%">ເວລາເຂົ້າ</th>
                                <th width="18%">ເວລາອອກ</th>
                                <th class="text-center" width="16%">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT f.*, a.airline_name FROM flight f JOIN airline a ON f.airline_id = a.airline_id ORDER BY f.flight_id DESC");
                            while($row = mysqli_fetch_assoc($res)) {
                                // ແປງຂໍ້ມູນເປັນ JSON ໂດຍຮັກສາພາສາລາວບໍ່ໃຫ້ເພ້ຍ
                                $jsonData = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                                
                                // ຈັດຮູບແບບເວລາ
                                $departure = !empty($row['departure_time']) ? date('d/m/Y H:i', strtotime($row['departure_time'])) : '-';
                                $arrival = !empty($row['arrival_time']) ? date('d/m/Y H:i', strtotime($row['arrival_time'])) : '-';
                                
                                echo "<tr>
                                    <td><span class='badge bg-secondary bg-opacity-10 text-secondary px-2 py-1.5 rounded'>#{$row['flight_id']}</span></td>
                                    <td><strong class='text-dark'>{$row['airline_name']}</strong></td>
                                    <td><span class='badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-3 fw-bold'><i class='fa-solid fa-plane me-1 small'></i>{$row['flight_number']}</span></td>
                                    <td class='fw-semibold text-secondary'>
                                        <span class='badge bg-success bg-opacity-10 text-success px-2 py-1'>{$row['origin']}</span>
                                        <i class='fa-solid fa-arrow-right mx-2 text-primary small'></i> 
                                        <span class='badge bg-danger bg-opacity-10 text-danger px-2 py-1'>{$row['destination']}</span>
                                    </td>
                                    <td><span class='time-badge'><i class='fa-regular fa-clock'></i> {$departure}</span></td>
                                    <td><span class='time-badge'><i class='fa-regular fa-clock'></i> {$arrival}</span></td>
                                    <td class='text-center'>
                                        <button type='button' class='btn btn-warning btn-sm btn-action text-white me-1' onclick='editFlight({$jsonData})'>
                                            <i class='fa-solid fa-pen-to-square me-1'></i> ແກ້ໄຂ
                                        </button>
                                        <a href='flight.php?delete={$row['flight_id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"ທ່ານຕ້ອງການລຶບຖ້ຽວບິນນີ້ແທ້ຫຼືບໍ່?\")'>
                                            <i class='fa-solid fa-trash-can me-1'></i> ລຶບ
                                        </a>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal ແກ້ໄຂ -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-0 bg-light rounded-top-4">
        <h5 class="modal-title fw-bold" id="editModalLabel"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> ແກ້ໄຂຂໍ້ມູນຖ້ຽວບິນ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
          <div class="modal-body p-4">
                <input type="hidden" name="flight_id" id="edit_id">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ສາຍການບິນ</label>
                        <select name="airline_id" id="edit_airline_id" class="form-select p-2 rounded-3" required>
                            <option value="">-- ເລືອກສາຍການບິນ --</option>
                            <?php 
                            $air_modal = mysqli_query($conn, "SELECT * FROM airline"); 
                            while($m = mysqli_fetch_assoc($air_modal)) {
                                echo "<option value='{$m['airline_id']}'>{$m['airline_name']}</option>"; 
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ເລກຖ້ຽວບິນ</label>
                        <input type="text" name="flight_number" id="edit_flight_number" class="form-control p-2 rounded-3" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ຕົ້ນທາງ (Origin)</label>
                        <select name="origin" id="edit_origin" class="form-select p-2 rounded-3 province-select" required>
                            <option value="">-- ເລືອກແຂວງ --</option>
                            <?php foreach($provinces as $province): ?>
                                <option value="<?= $province ?>"><?= $province ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ປາຍທາງ (Destination)</label>
                        <select name="destination" id="edit_destination" class="form-select p-2 rounded-3 province-select" required>
                            <option value="">-- ເລືອກແຂວງ --</option>
                            <?php foreach($provinces as $province): ?>
                                <option value="<?= $province ?>"><?= $province ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ເວລາເຂົ້າ (Departure)</label>
                        <input type="datetime-local" name="departure_time" id="edit_departure_time" class="form-control p-2 rounded-3" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ເວລາອອກ (Arrival)</label>
                        <input type="datetime-local" name="arrival_time" id="edit_arrival_time" class="form-control p-2 rounded-3" required>
                    </div>
                </div>
          </div>
          <div class="modal-footer border-0 bg-light rounded-bottom-4">
            <button type="button" class="btn btn-secondary border-0 bg-opacity-10 text-dark rounded-3 px-3" data-bs-dismiss="modal">ຍົກເລີກ</button>
            <button type="submit" name="update" class="btn btn-warning text-white rounded-3 px-4 fw-semibold">ບັນທຶກການແກ້ໄຂ</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editFlight(data) {
    // ປ້ອນຂໍ້ມູນເກົ່າເຂົ້າໄປໃນ Input ແຕ່ລະຊ່ອງຂອງ Modal
    document.getElementById('edit_id').value = data.flight_id;
    document.getElementById('edit_airline_id').value = data.airline_id;
    document.getElementById('edit_flight_number').value = data.flight_number;
    document.getElementById('edit_origin').value = data.origin;
    document.getElementById('edit_destination').value = data.destination;
    
    // ຈັດຮູບແບບເວລາໃຫ້ເຂົ້າກັບ input type datetime-local
    if (data.departure_time) {
        document.getElementById('edit_departure_time').value = data.departure_time.substring(0, 16);
    }
    if (data.arrival_time) {
        document.getElementById('edit_arrival_time').value = data.arrival_time.substring(0, 16);
    }
    
    // ສັ່ງເປີດ Modal ປ໊ອບອັບ
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>
</body>
</html>