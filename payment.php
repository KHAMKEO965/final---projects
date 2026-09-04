<?php
session_start();


include 'db.php';

// 1. CREATE: ບັນທຶກການຊຳລະເງິນ
if (isset($_POST['add'])) {
    $ship_id = intval($_POST['shipment_id']);
    $amount = floatval($_POST['amount']);
    $p_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    
    mysqli_query($conn, "INSERT INTO payment (shipment_id, amount, payment_date) VALUES ('$ship_id', '$amount', '$p_date')");
    header("Location: payment.php"); exit();
}

// 2. UPDATE: ແກ້ໄຂຂໍ້ມູນການຊຳລະເງິນ
if (isset($_POST['update'])) {
    $id = intval($_POST['payment_id']);
    $ship_id = intval($_POST['shipment_id']);
    $amount = floatval($_POST['amount']);
    $p_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    
    mysqli_query($conn, "UPDATE payment SET shipment_id='$ship_id', amount='$amount', payment_date='$p_date' WHERE payment_id=$id");
    header("Location: payment.php"); exit();
}

// 3. DELETE: ລຶບປະຫວັດການຊຳລະເງິນ
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM payment WHERE payment_id = $id");
    header("Location: payment.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຈັດການການຊຳລະເງິນ</title>
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
                    <i class="fa-solid fa-wallet text-primary me-2"></i> ຈັດການການຊຳລະເງິນ
                </h3>
                
                <form method="POST" class="row g-3 p-3 bg-light rounded-4">
                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-semibold">ເລືອກຮອບການສົ່ງສິນຄ້າ</label>
                        <select name="shipment_id" class="form-select p-2 rounded-3" required>
                            <option value="">-- ເລືອກລະຫັດການສົ່ງ --</option>
                            <?php 
                            $s = mysqli_query($conn, "SELECT s.shipment_id, c.customer_name FROM shipment s JOIN customer c ON s.customer_id = c.customer_id ORDER BY s.shipment_id DESC"); 
                            while($r=mysqli_fetch_assoc($s)) {
                                echo "<option value='{$r['shipment_id']}'>ID: #SH-" . str_pad($r['shipment_id'], 4, "0", STR_PAD_LEFT) . " - {$r['customer_name']}</option>"; 
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-semibold">ຈຳນວນເງິນຊຳລະ (LAK)</label>
                        <input type="number" step="0.01" name="amount" class="form-control p-2 rounded-3" placeholder="0.00" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-semibold">ວັນທີຊຳລະເງິນ</label>
                        <input type="date" name="payment_date" class="form-control p-2 rounded-3" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" name="add" class="btn btn-primary p-2 w-100 rounded-3 shadow-sm fw-semibold">
                            <i class="fa-solid fa-receipt me-1"></i> ບັນທຶກຊຳລະ
                        </button>
                    </div>
                </form>
            </div>

            <div class="card p-3 bg-white">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead>
                              /// ສຳລັບສະແດງຕາຕະລາງຊໍາລະ
                            <tr>
                                <th width="10%">ID ບິນຊຳລະ</th>
                                <th>ລະຫັດການສົ່ງ</th>
                                <th>ຊື່ລູກຄ້າ</th>
                                <th>ຈຳນວນເງິນຊຳລະ</th>
                                <th>ວັນທີຊຳລະ</th>
                                <th class="text-center" width="18%">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // ປັບ Query ໃຫ້ JOIN ເພື່ອດຶງຊື່ລູກຄ້າມາສະແດງໃນຕາຕະລາງນຳ
                            $sql = "SELECT p.*, s.customer_id, c.customer_name 
                                    FROM payment p
                                    JOIN shipment s ON p.shipment_id = s.shipment_id
                                    JOIN customer c ON s.customer_id = c.customer_id
                                    ORDER BY p.payment_id DESC";
                            $res = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($res)) {
                                $jsonData = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                                
                                echo "<tr>
                                    <td><span class='badge bg-secondary bg-opacity-10 text-secondary px-2 py-1.5 rounded'>#PAY-" . str_pad($row['payment_id'], 4, "0", STR_PAD_LEFT) . "</span></td>
                                    <td class='fw-bold text-primary'>#SH-" . str_pad($row['shipment_id'], 4, "0", STR_PAD_LEFT) . "</td>
                                    <td class='fw-semibold text-dark'>{$row['customer_name']}</td>
                                    <td class='text-success fw-bold'><i class='fa-solid fa-circle-check me-1 small opacity-75'></i>" . number_format($row['amount'], 0) . " ກີບ</td>
                                    <td><span class='text-muted small'>" . date('d-m-Y', strtotime($row['payment_date'])) . "</span></td>
                                    <td class='text-center'>
                                        <button type='button' class='btn btn-warning btn-sm btn-action text-white me-1' onclick='editPayment({$jsonData})'>
                                            <i class='fa-solid fa-pen-to-square'></i> ແກ້ໄຂ
                                        </button>
                                        <a href='payment.php?delete={$row['payment_id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"ຢືນຢັນການລຶບປະຫວັດການຊຳລະເງິນນີ້?\")'>
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
        <h5 class="modal-title fw-bold" id="editModalLabel"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> ແກ້ໄຂຂໍ້ມູນການຊຳລະເງິນ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
          <div class="modal-body p-4">
                <input type="hidden" name="payment_id" id="edit_id">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ຮອບການສົ່ງສິນຄ້າ</label>
                    <select name="shipment_id" id="edit_shipment_id" class="form-select p-2 rounded-3" required>
                        <?php 
                        $s_modal = mysqli_query($conn, "SELECT s.shipment_id, c.customer_name FROM shipment s JOIN customer c ON s.customer_id = c.customer_id"); 
                        while($r=mysqli_fetch_assoc($s_modal)) {
                            echo "<option value='{$r['shipment_id']}'>ID: #SH-" . str_pad($r['shipment_id'], 4, "0", STR_PAD_LEFT) . " - {$r['customer_name']}</option>"; 
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ຈຳນວນເງິນຊຳລະ (LAK)</label>
                    <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control p-2 rounded-3" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ວັນທີຊຳລະເງິນ</label>
                    <input type="date" name="payment_date" id="edit_payment_date" class="form-control p-2 rounded-3" required>
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
function editPayment(data) {
    document.getElementById('edit_id').value = data.payment_id;
    document.getElementById('edit_shipment_id').value = data.shipment_id;
    document.getElementById('edit_amount').value = data.amount;
    document.getElementById('edit_payment_date').value = data.payment_date;
    
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>
</body>
</html>