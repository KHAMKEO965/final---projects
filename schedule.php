<?php
session_start();


include 'db.php';

// 1. CREATE: ເພີ່ມຂໍ້ມູນຕາຕະລາງບິນ
if (isset($_POST['add'])) {
    $flight_id = intval($_POST['flight_id']);
    $dep_time = mysqli_real_escape_string($conn, $_POST['departure_time']);
    $arr_time = mysqli_real_escape_string($conn, $_POST['arrival_time']);
    
    mysqli_query($conn, "INSERT INTO schedule (flight_id, departure_time, arrival_time) VALUES ('$flight_id', '$dep_time', '$arr_time')");
    header("Location: schedule.php"); exit();
}

// 2. UPDATE: ແກ້ໄຂຂໍ້ມູນຕາຕະລາງບິນ
if (isset($_POST['update'])) {
    $id = intval($_POST['schedule_id']);
    $flight_id = intval($_POST['flight_id']);
    $dep_time = mysqli_real_escape_string($conn, $_POST['departure_time']);
    $arr_time = mysqli_real_escape_string($conn, $_POST['arrival_time']);
    
    mysqli_query($conn, "UPDATE schedule SET flight_id='$flight_id', departure_time='$dep_time', arrival_time='$arr_time' WHERE schedule_id=$id");
    header("Location: schedule.php"); exit();
}

// 3. DELETE: ລຶບຂໍ້ມູນຕາຕະລາງບິນ
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM schedule WHERE schedule_id = $id");
    header("Location: schedule.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຈັດການຕາຕະລາງບິນ</title>
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
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 500;
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
                    <i class="fa-solid fa-calendar-days text-primary me-2"></i> ຈັດການຕາຕະລາງການບິນ
                </h3>
                
                <form method="POST" class="row g-3 p-3 bg-light rounded-4">
                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-semibold">ເລືອກຖ້ຽວບິນ</label>
                        <select name="flight_id" class="form-select p-2 rounded-3" required>
                            <option value="">-- ເລືອກຖ້ຽວບິນ --</option>
                            <?php 
                            $fl = mysqli_query($conn, "SELECT * FROM flight ORDER BY flight_id DESC"); 
                            while($r = mysqli_fetch_assoc($fl)) {
                                echo "<option value='{$r['flight_id']}'>{$r['flight_number']} ({$r['origin']} - {$r['destination']})</option>"; 
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-semibold">ເວລາອອກ (Departure Time)</label>
                        <input type="datetime-local" name="departure_time" class="form-control p-2 rounded-3" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-semibold">ເວລາຮອດ (Arrival Time)</label>
                        <input type="datetime-local" name="arrival_time" class="form-control p-2 rounded-3" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" name="add" class="btn btn-primary p-2 w-100 rounded-3 shadow-sm fw-semibold">
                            <i class="fa-solid fa-calendar-plus me-1"></i> ບັນທຶກ
                        </button>
                    </div>
                </form>
            </div>

            <div class="card p-3 bg-white">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="8%">ID</th>
                                <th>ເລກຖ້ຽວບິນ</th>
                                <th>ເວລາອອກ (Departure)</th>
                                <th>ເວລາຮອດ (Arrival)</th>
                                <th class="text-center" width="18%">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT s.*, f.flight_number FROM schedule s JOIN flight f ON s.flight_id = f.flight_id ORDER BY s.schedule_id DESC");
                            while($row = mysqli_fetch_assoc($res)) {
                                // ຟໍແມັດຄ່າວັນທີ-ເວລາໃຫ້ຮອງຮັບກັບຮູບແບບ input datetime-local ໃນ Modal (Y-m-d\TH:i)
                                $row['dep_modal_format'] = date('Y-m-d\TH:i', strtotime($row['departure_time']));
                                $row['arr_modal_format'] = date('Y-m-d\TH:i', strtotime($row['arrival_time']));
                                
                                $jsonData = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                                
                                echo "<tr>
                                    <td><span class='badge bg-secondary bg-opacity-10 text-secondary px-2 py-1.5 rounded'>#S-{$row['schedule_id']}</span></td>
                                    <td><span class='badge bg-dark bg-opacity-10 text-dark px-3 py-2 rounded-3 fw-bold'><i class='fa-solid fa-plane me-1 small'></i>{$row['flight_number']}</span></td>
                                    <td class='text-primary fw-semibold'><i class='fa-solid fa-plane-departure me-2 opacity-50'></i>" . date('d-m-Y H:i', strtotime($row['departure_time'])) . "</td>
                                    <td class='text-success fw-semibold'><i class='fa-solid fa-plane-arrival me-2 opacity-50'></i>" . date('d-m-Y H:i', strtotime($row['arrival_time'])) . "</td>
                                    <td class='text-center'>
                                        <button type='button' class='btn btn-warning btn-sm btn-action text-white me-1' onclick='editSchedule({$jsonData})'>
                                            <i class='fa-solid fa-pen-to-square'></i> ແກ້ໄຂ
                                        </button>
                                        <a href='schedule.php?delete={$row['schedule_id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"ຢືນຢັນການລຶບຕາຕະລາງການບິນນີ້?\")'>
                                            <i class='fa-solid fa-trash-can'></i>
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-0 bg-light rounded-top-4">
        <h5 class="modal-title fw-bold" id="editModalLabel"><i class="fa-solid fa-calendar-days text-warning me-2"></i> ແກ້ໄຂຕາຕະລາງການບິນ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
          <div class="modal-body p-4">
                <input type="hidden" name="schedule_id" id="edit_id">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ເລືອກຖ້ຽວບິນ</label>
                    <select name="flight_id" id="edit_flight_id" class="form-select p-2 rounded-3" required>
                        <?php 
                        $fl_modal = mysqli_query($conn, "SELECT * FROM flight"); 
                        while($m = mysqli_fetch_assoc($fl_modal)) {
                            echo "<option value='{$m['flight_id']}'>{$m['flight_number']} ({$m['origin']}-{$m['destination']})</option>"; 
                        }
                        ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ເວລາອອກ (Departure Time)</label>
                    <input type="datetime-local" name="departure_time" id="edit_departure_time" class="form-control p-2 rounded-3" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ເວລາຮອດ (Arrival Time)</label>
                    <input type="datetime-local" name="arrival_time" id="edit_arrival_time" class="form-control p-2 rounded-3" required>
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
function editSchedule(data) {
    document.getElementById('edit_id').value = data.schedule_id;
    document.getElementById('edit_flight_id').value = data.flight_id;
    
    // ນຳໃຊ້ຄ່າຟໍແມັດພິເສດ (Y-m-d\TH:i) ທີ່ກຽມໄວ້ໃນ PHP ມາໃສ່ໃນ Input datetime-local
    document.getElementById('edit_departure_time').value = data.dep_modal_format;
    document.getElementById('edit_arrival_time').value = data.arr_modal_format;
    
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>
</body>
</html>