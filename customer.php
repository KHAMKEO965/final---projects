<?php
session_start();

// 1. ກວດສອບ Session: ຖ້າບໍ່ທັນ Login ໃຫ້ສົ່ງໄປໜ້າ login.php (ປ່ຽນຊື່ໄຟລ໌ໄດ້ຕາມຈິງ)


// 2. ເຊື່ອມຕໍ່ຖານຂໍ້ມູນສະເໝີ
include 'db.php';

// --- ເພີ່ມຂໍ້ມູນ (Create) ---
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    mysqli_query($conn, "INSERT INTO customer (customer_name, phone, email, address) VALUES ('$name', '$phone', '$email', '$address')");
    header("Location: customer.php"); exit();
}

// --- ແກ້ໄຂຂໍ້ມູນ (Update) ---
if (isset($_POST['update'])) {
    $id = intval($_POST['customer_id']);
    $name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    mysqli_query($conn, "UPDATE customer SET customer_name='$name', phone='$phone', email='$email', address='$address' WHERE customer_id=$id");
    header("Location: customer.php"); exit();
}

// --- ລຶບຂໍ້ມູນ (Delete) ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM customer WHERE customer_id = $id");
    header("Location: customer.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຈັດການຂໍ້ມູນລູກຄ້າ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Phetsarath OT', 'Noto Sans Lao', sans-serif; bg-color: #f8f9fa; }
        .card { border: none; border-radius: 16px; transition: all 0.3s ease; }
        .table th { font-weight: 600; background-color: #f1f3f9 !important; }
        .btn-action { padding: 6px 12px; border-radius: 8px; }
    </style>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <?php include 'navbar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="card shadow-sm p-4 mb-4 bg-white">
                <h3 class="fw-bold text-dark mb-4">
                    <i class="fa-solid fa-users text-primary me-2"></i> ຈັດການຂໍ້ມູນລູກຄ້າ
                </h3>
                
                <form method="POST" class="row g-3 p-3 bg-light rounded-4">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">ຊື່ລູກຄ້າ</label>
                        <input type="text" name="customer_name" class="form-control border-0 shadow-sm p-2" placeholder="ປ້ອນຊື່ລູກຄ້າ" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">ເບີໂທ</label>
                        <input type="text" name="phone" class="form-control border-0 shadow-sm p-2" placeholder="ເບີໂທລະສັບ" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">ອີເມວ</label>
                        <input type="email" name="email" class="form-control border-0 shadow-sm p-2" placeholder="example@gmail.com">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">ທີ່ຢູ່</label>
                        <input type="text" name="address" class="form-control border-0 shadow-sm p-2" placeholder="ແຂວງ, ເມືອງ, ບ້ານ">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" name="add" class="btn btn-primary p-2 w-100 shadow-sm" style="border-radius: 8px;">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card shadow-sm p-3 bg-white">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead>
                        
                             <tr>
                                <th width="8%">ID</th>
                                <th>ຊື່ລູກຄ້າ</th>
                                <th>ເບີໂທ</th>
                                <th>ອີເມວ</th>
                                <th>ທີ່ຢູ່</th>
                                <th class="text-center" width="18%">ຈັດການ</th>
                            </tr>
                            
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM customer ORDER BY customer_id DESC");
                            while($row = mysqli_fetch_assoc($res)) {
                                // ແປງຂໍ້ມູນເປັນ JSON ເພື່ອສົ່ງໄປຫາ Modal ແກ້ໄຂ
                                $jsonData = json_encode($row, JSON_UNESCAPED_UNICODE);
                                echo "<tr>
                                    <td><span class='badge bg-secondary bg-opacity-10 text-secondary px-2 py-1'>#{$row['customer_id']}</span></td>
                                    <td class='fw-semibold text-dark'>{$row['customer_name']}</td>
                                    <td><i class='fa-solid fa-phone text-muted me-2 small'></i>{$row['phone']}</td>
                                    <td><span class='text-muted'>".($row['email'] ? $row['email'] : '-')."</span></td>
                                    <td>{$row['address']}</td>
                                    <td class='text-center'>
                                        <button class='btn btn-warning btn-sm btn-action text-white me-1' onclick='editCustomer({$jsonData})'>
                                            <i class='fa-solid fa-pen-to-square'></i> ແກ້ໄຂ
                                        </button>
                                        <a href='customer.php?delete={$row['customer_id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"ຢືນຢັນການລຶບລູກຄ້ານີ້?\")'>
                                            <i class='fa-solid fa-trash-can'></i> ລຶບ
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

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-0 bg-light">
        <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-pen text-warning me-2"></i> ແກ້ໄຂຂໍ້ມູນລູກຄ້າ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" name="customer_id" id="edit_id">
        <div class="mb-3">
            <label class="form-label small fw-bold">ຊື່ລູກຄ້າ</label>
            <input type="text" name="customer_name" id="edit_name" class="form-control p-2" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold">ເບີໂທ</label>
            <input type="text" name="phone" id="edit_phone" class="form-control p-2" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold">ອີເມວ</label>
            <input type="email" name="email" id="edit_email" class="form-control p-2">
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold">ທີ່ຢູ່</label>
            <input type="text" name="address" id="edit_address" class="form-control p-2">
        </div>
      </div>
      <div class="modal-footer border-0 bg-light">
        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 8px;">ຍົກເລີກ</button>
        <button type="submit" name="update" class="btn btn-warning text-white px-4" style="border-radius: 8px;">ບັນທຶກການປ່ຽນແປງ</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editCustomer(data) {
    document.getElementById('edit_id').value = data.customer_id;
    document.getElementById('edit_name').value = data.customer_name;
    document.getElementById('edit_phone').value = data.phone;
    document.getElementById('edit_email').value = data.email;
    document.getElementById('edit_address').value = data.address;
    
    // ເປີດ Modal
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>
</body>
</html>