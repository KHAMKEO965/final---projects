<?php
session_start();


include 'db.php';

// 1. CREATE: ເພີ່ມຂໍ້ມູນສາຍການບິນ
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['airline_name']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    mysqli_query($conn, "INSERT INTO airline (airline_name, country, contact) VALUES ('$name', '$country', '$contact')");
    header("Location: airline.php"); exit();
}

// 2. UPDATE: ແກ້ໄຂຂໍ້ມູນສາຍການບິນ
if (isset($_POST['update'])) {
    $id = intval($_POST['airline_id']);
    $name = mysqli_real_escape_string($conn, $_POST['airline_name']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    
    mysqli_query($conn, "UPDATE airline SET airline_name='$name', country='$country', contact='$contact' WHERE airline_id=$id");
    header("Location: airline.php"); exit();
}

// 3. DELETE: ລຶບຂໍ້ມູນສາຍການບິນ
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM airline WHERE airline_id = $id");
    header("Location: airline.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຈັດການຂໍ້ມູນສາຍການບິນ</title>
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
            box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
        }
        .form-control {
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }
        .form-control:focus {
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
        /* ເພີ່ມສີສຳລັບແຂວງ */
        .province-badge {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        .province-badge i {
            margin-right: 6px;
            color: #43a047;
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
                    <i class="fa-solid fa-plane-departure text-primary me-2"></i> ຈັດການຂໍ້ມູນສາຍການບິນ
                </h3>
                
                <form method="POST" class="row g-3 p-3 bg-light rounded-4">
                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-semibold">ຊື່ສາຍການບິນ</label>
                        <input type="text" name="airline_name" class="form-control p-2-5 rounded-3" placeholder="ປ້ອນຊື່ສາຍການບິນ" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-semibold">ແຂວງ</label>
                        <select name="province" class="form-select p-2-5 rounded-3" required>
                            <option value="">-- ເລືອກແຂວງ --</option>
                            <option value="ວຽງຈັນ">ວຽງຈັນ</option>
                            <option value="ຫຼວງພະບາງ">ຫຼວງພະບາງ</option>
                            <option value="ຈຳປາສັກ">ຈຳປາສັກ</option>
                            <option value="ສະຫວັນນະເຂດ">ສະຫວັນນະເຂດ</option>
                            <option value="ອຸດົມໄຊ">ອຸດົມໄຊ</option>
                            <option value="ຫົວພັນ">ຫົວພັນ</option>
                            <option value="ຄຳມ່ວນ">ຄຳມ່ວນ</option>
                            <option value="ບໍ່ແກ້ວ">ບໍ່ແກ້ວ</option>
                            <option value="ໄຊຍະບູລີ">ໄຊຍະບູລີ</option>
                            <option value="ສາລາວັນ">ສາລາວັນ</option>
                            <option value="ເຊກອງ">ເຊກອງ</option>
                            <option value="ອັດຕະປື">ອັດຕະປື</option>
                            <option value="ໄຊສົມບູນ">ໄຊສົມບູນ</option>
                            <option value="ຜົ້ງສາລີ">ຜົ້ງສາລີ</option>
                            <option value="ຫຼວງນ້ຳທາ">ຫຼວງນ້ຳທາ</option>
                            <option value="ບໍລິຄຳໄຊ">ບໍລິຄຳໄຊ</option>
                            <option value="ຊຽງຂວາງ">ຊຽງຂວາງ</option>
                            <option value="ວຽງຈັນ (ນະຄອນຫຼວງ)">ວຽງຈັນ (ນະຄອນຫຼວງ)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-semibold">ເບີຕິດຕໍ່</label>
                        <input type="text" name="contact" class="form-control p-2-5 rounded-3" placeholder="ເບີໂທ ຫຼື ອີເມວ">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" name="add" class="btn btn-primary p-2-5 w-100 rounded-3 shadow-sm fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> ບັນທຶກຂໍ້ມູນ
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
                                <th>ຊື່ສາຍການບິນ</th>
                                <th>ແຂວງ</th>
                                <th>ເບີຕິດຕໍ່</th>
                                <th class="text-center" width="20%">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM airline ORDER BY airline_id DESC");
                            while($row = mysqli_fetch_assoc($res)) {
                                $jsonData = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                                
                                echo "<tr>
                                    <td><span class='badge bg-secondary bg-opacity-10 text-secondary px-2 py-1.5 rounded'>#{$row['airline_id']}</span></td>
                                    <td class='fw-semibold text-dark'>{$row['airline_name']}</td>
                                    <td>
                                        <span class='province-badge'>
                                        </span>
                                    </td>
                                    <td><span class='text-muted'>".($row['contact'] ? $row['contact'] : '-')."</span></td>
                                    <td class='text-center'>
                                        <button type='button' class='btn btn-warning btn-sm btn-action text-white me-1' onclick='editAirline({$jsonData})'>
                                            <i class='fa-solid fa-pen-to-square me-1'></i> ແກ້ໄຂ
                                        </button>
                                        <a href='airline.php?delete={$row['airline_id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບສາຍການບິນນີ້?\")'>
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-0 bg-light rounded-top-4">
        <h5 class="modal-title fw-bold" id="editModalLabel"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> ແກ້ໄຂຂໍ້ມູນສາຍການບິນ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
          <div class="modal-body p-4">
                <input type="hidden" name="airline_id" id="edit_id">
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ຊື່ສາຍການບິນ</label>
                    <input type="text" name="airline_name" id="edit_name" class="form-control p-2 rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ແຂວງ</label>
                    <select name="province" id="edit_province" class="form-select p-2 rounded-3" required>
                        <option value="">-- ເລືອກແຂວງ --</option>
                        <option value="ວຽງຈັນ">ວຽງຈັນ</option>
                        <option value="ຫຼວງພະບາງ">ຫຼວງພະບາງ</option>
                        <option value="ຈຳປາສັກ">ຈຳປາສັກ</option>
                        <option value="ສະຫວັນນະເຂດ">ສະຫວັນນະເຂດ</option>
                        <option value="ອຸດົມໄຊ">ອຸດົມໄຊ</option>
                        <option value="ຫົວພັນ">ຫົວພັນ</option>
                        <option value="ຄຳມ່ວນ">ຄຳມ່ວນ</option>
                        <option value="ບໍ່ແກ້ວ">ບໍ່ແກ້ວ</option>
                        <option value="ໄຊຍະບູລີ">ໄຊຍະບູລີ</option>
                        <option value="ສາລາວັນ">ສາລາວັນ</option>
                        <option value="ເຊກອງ">ເຊກອງ</option>
                        <option value="ອັດຕະປື">ອັດຕະປື</option>
                        <option value="ໄຊສົມບູນ">ໄຊສົມບູນ</option>
                        <option value="ຜົ້ງສາລີ">ຜົ້ງສາລີ</option>
                        <option value="ຫຼວງນ້ຳທາ">ຫຼວງນ້ຳທາ</option>
                        <option value="ບໍລິຄຳໄຊ">ບໍລິຄຳໄຊ</option>
                        <option value="ຊຽງຂວາງ">ຊຽງຂວາງ</option>
                        <option value="ວຽງຈັນ (ນະຄອນຫຼວງ)">ວຽງຈັນ (ນະຄອນຫຼວງ)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ເບີຕິດຕໍ່</label>
                    <input type="text" name="contact" id="edit_contact" class="form-control p-2 rounded-3">
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
function editAirline(data) {
    document.getElementById('edit_id').value = data.airline_id;
    document.getElementById('edit_name').value = data.airline_name;
    document.getElementById('edit_province').value = data.province;
    document.getElementById('edit_contact').value = data.contact;
    
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>
</body>
</html>