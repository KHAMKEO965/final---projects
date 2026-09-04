<?php
session_start();



include 'db.php';

// 1. CREATE: ເພີ່ມຂໍ້ມູນການຂົນສົ່ງ
if (isset($_POST['add'])) {
    $cust_id = intval($_POST['customer_id']);
    $prod_id = intval($_POST['product_id']);
    $flit_id = intval($_POST['flight_id']);
    $s_date = mysqli_real_escape_string($conn, $_POST['shipment_date']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    mysqli_query($conn, "INSERT INTO shipment (customer_id, product_id, flight_id, shipment_date, status) VALUES ('$cust_id', '$prod_id', '$flit_id', '$s_date', '$status')");
    header("Location: shipment.php"); exit();
}

// 2. UPDATE: ແກ້ໄຂຂໍ້ມູນການຂົນສົ່ງ
if (isset($_POST['update'])) {
    $id = intval($_POST['shipment_id']);
    $cust_id = intval($_POST['customer_id']);
    $prod_id = intval($_POST['product_id']);
    $flit_id = intval($_POST['flight_id']);
    $s_date = mysqli_real_escape_string($conn, $_POST['shipment_date']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    mysqli_query($conn, "UPDATE shipment SET customer_id='$cust_id', product_id='$prod_id', flight_id='$flit_id', shipment_date='$s_date', status='$status' WHERE shipment_id=$id");
    header("Location: shipment.php"); exit();
}

// 3. DELETE: ລຶບຂໍ້ມູນການຂົນສົ່ງ
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM shipment WHERE shipment_id = $id");
    header("Location: shipment.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຈັດການການຂົນສົ່ງ</title>
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
                    <i class="fa-solid fa-truck-ramp-box text-primary me-2"></i> ຈັດການການຂົນສົ່ງ (Shipment)
                </h3>
                
                <form method="POST" class="row g-3 p-3 bg-light rounded-4">
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ເລືອກລູກຄ້າ</label>
                        <select name="customer_id" class="form-select p-2 rounded-3" required>
                            <option value="">-- ເລືອກລູກຄ້າ --</option>
                            <?php $c = mysqli_query($conn, "SELECT * FROM customer"); while($r=mysqli_fetch_assoc($c)) echo "<option value='{$r['customer_id']}'>{$r['customer_name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ເລືອກສິນຄ້າ</label>
                        <select name="product_id" class="form-select p-2 rounded-3" required>
                            <option value="">-- ເລືອກສິນຄ້າ --</option>
                            <?php $p = mysqli_query($conn, "SELECT * FROM product"); while($r=mysqli_fetch_assoc($p)) echo "<option value='{$r['product_id']}'>{$r['product_name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ເລືອກຖ້ຽວບິນ</label>
                        <select name="flight_id" class="form-select p-2 rounded-3" required>
                            <option value="">-- ເລືອກຖ້ຽວບິນ --</option>
                            <?php $f = mysqli_query($conn, "SELECT * FROM flight"); while($r=mysqli_fetch_assoc($f)) echo "<option value='{$r['flight_id']}'>{$r['flight_number']}</option>"; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-semibold">ວັນທີສົ່ງສິນຄ້າ</label>
                        <input type="date" name="shipment_date" class="form-control p-2 rounded-3" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ສະຖານະ</label>
                        <select name="status" class="form-select p-2 rounded-3">
                            <option value="ກຳລັງຮຽນຕຽມ">📦 ກຳລັງຕຽມການສົ່ງ</option>
                            <option value="ກຳລັງຂົນສົ່ງ">🚚 ກຳລັງຂົນສົ່ງ</option>
                            <option value="ສຳເລັດແລ້ວ">✅ ສຳເລັດແລ້ວ</option>
                        </select>
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
                                <th>ID ໃບບິນ</th>
                                <th>ລູກຄ້າ</th>
                                <th>ສິນຄ້າ</th>
                                <th>ຖ້ຽວບິນ</th>
                                <th>ວັນທີສົ່ງ</th>
                                <th>ສະຖານະ</th>
                                <th>ພິມ</th>
                                <th class="text-center" width="18%">ຈັດການ</th>
                            </tr>
                            
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT s.*, c.customer_name, p.product_name, f.flight_number FROM shipment s 
                                    JOIN customer c ON s.customer_id = c.customer_id
                                    JOIN product p ON s.product_id = p.product_id
                                    JOIN flight f ON s.flight_id = f.flight_id ORDER BY s.shipment_id DESC";
                            $res = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($res)) {
                                // ກວດສອບສະຖານະເພື່ອແຍກສີ Badge (Soft Palette)
                                if ($row['status'] == 'ສຳເລັດແລ້ວ') {
                                    $badge = 'bg-success text-success';
                                } elseif ($row['status'] == 'ກຳລັງຂົນສົ່ງ') {
                                    $badge = 'bg-primary text-primary';
                                } else {
                                    $badge = 'bg-warning text-warning';
                                }
                                
                                $jsonData = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                                
                                echo "<tr>
                                    <td class='fw-bold text-dark'>#SH-" . str_pad($row['shipment_id'], 4, "0", STR_PAD_LEFT) . "</td>
                                    <td class='fw-semibold'>{$row['customer_name']}</td>
                                    <td><i class='fa-solid fa-box text-muted me-1 small'></i> {$row['product_name']}</td>
                                    <td><span class='badge bg-secondary bg-opacity-10 text-secondary px-2 py-1.5 rounded'><i class='fa-solid fa-plane me-1 small'></i>{$row['flight_number']}</span></td>
                                    <td><span class='text-muted small'>" . date('d-m-Y', strtotime($row['shipment_date'])) . "</span></td>
                                    <td><span class='badge $badge bg-opacity-10 px-3 py-2 rounded-pill fw-medium'>{$row['status']}</span></td>
                                    <td>
                                        <a href='invoice.php?id={$row['shipment_id']}' target='_blank' class='btn btn-light btn-sm rounded-3 fw-bold px-2 text-dark border'>
                                            <i class='fa-solid fa-print text-muted me-1'></i> ໃບບິນ
                                        </a>
                                    </td>
                                    <td class='text-center'>
                                        <button type='button' class='btn btn-warning btn-sm btn-action text-white me-1' onclick='editShipment({$jsonData})'>
                                            <i class='fa-solid fa-pen-to-square'></i>
                                        </button>
                                        <a href='shipment.php?delete={$row['shipment_id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"ຢືນຢັນການລຶບລາຍການຂົນສົ່ງນີ້?\")'>
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
        <h5 class="modal-title fw-bold" id="editModalLabel"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> ແກ້ໄຂຂໍ້ມູນການຂົນສົ່ງ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
          <div class="modal-body p-4">
                <input type="hidden" name="shipment_id" id="edit_id">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ລູກຄ້າ</label>
                    <select name="customer_id" id="edit_customer_id" class="form-select p-2 rounded-3" required>
                        <?php $c_m = mysqli_query($conn, "SELECT * FROM customer"); while($r=mysqli_fetch_assoc($c_m)) echo "<option value='{$r['customer_id']}'>{$r['customer_name']}</option>"; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ສິນຄ້າ</label>
                    <select name="product_id" id="edit_product_id" class="form-select p-2 rounded-3" required>
                        <?php $p_m = mysqli_query($conn, "SELECT * FROM product"); while($r=mysqli_fetch_assoc($p_m)) echo "<option value='{$r['product_id']}'>{$r['product_name']}</option>"; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ຖ້ຽວບິນ</label>
                    <select name="flight_id" id="edit_flight_id" class="form-select p-2 rounded-3" required>
                        <?php $f_m = mysqli_query($conn, "SELECT * FROM flight"); while($r=mysqli_fetch_assoc($f_m)) echo "<option value='{$r['flight_id']}'>{$r['flight_number']}</option>"; ?>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ວັນທີສົ່ງ</label>
                        <input type="date" name="shipment_date" id="edit_shipment_date" class="form-control p-2 rounded-3" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ສະຖານະ</label>
                        <select name="status" id="edit_status" class="form-select p-2 rounded-3">
                            <option value="ກຳລັງຮຽນຕຽມ">ກຳລັງຕຽມການສົ່ງ</option>
                            <option value="ກຳລັງຂົນສົ່ງ">ກຳລັງຂົນສົ່ງ</option>
                            <option value="ສຳເລັດແລ້ວ">ສຳເລັດແລ້ວ</option>
                        </select>
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
function editShipment(data) {
    document.getElementById('edit_id').value = data.shipment_id;
    document.getElementById('edit_customer_id').value = data.customer_id;
    document.getElementById('edit_product_id').value = data.product_id;
    document.getElementById('edit_flight_id').value = data.flight_id;
    document.getElementById('edit_shipment_date').value = data.shipment_date;
    document.getElementById('edit_status').value = data.status;
    
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>
</body>
</html>