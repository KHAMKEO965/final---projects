<?php
session_start();

include 'db.php';

// 1. CREATE: ເພີ່ມຂໍ້ມູນສິນຄ້າ
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $weight = floatval($_POST['weight']);
    $quantity = intval($_POST['quantity']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    mysqli_query($conn, "INSERT INTO product (product_name, weight, quantity, description, category) VALUES ('$name', '$weight', '$quantity', '$desc', '$category')");
    header("Location: product.php"); exit();
}

// 2. UPDATE: ແກ້ໄຂຂໍ້ມູນສິນຄ້າ
if (isset($_POST['update'])) {
    $id = intval($_POST['product_id']);
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $weight = floatval($_POST['weight']);
    $quantity = intval($_POST['quantity']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    mysqli_query($conn, "UPDATE product SET product_name='$name', weight='$weight', quantity='$quantity', description='$desc', category='$category' WHERE product_id=$id");
    header("Location: product.php"); exit();
}

// 3. DELETE: ລຶບຂໍ້ມູນສິນຄ້າ
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM product WHERE product_id = $id");
    header("Location: product.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຈັດການຂໍ້ມູນສິນຄ້າ</title>
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
        .badge.bg-secondary.bg-opacity-10 {
            background-color: rgba(108, 117, 125, 0.1) !important;
        }
        .badge.bg-info.bg-opacity-10 {
            background-color: rgba(13, 202, 240, 0.1) !important;
        }
        .badge.bg-success.bg-opacity-10 {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }
        .badge.bg-warning.bg-opacity-10 {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }
        .badge.bg-danger.bg-opacity-10 {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }
        .badge.bg-primary.bg-opacity-10 {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }
        .category-badge {
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 20px;
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
                    <i class="fa-solid fa-box-open text-primary me-2"></i> ຈັດການຂໍ້ມູນສິນຄ້າ
                </h3>
                
                <form method="POST" class="row g-3 p-3 bg-light rounded-4">
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-semibold">ຊື່ສິນຄ້າ</label>
                        <input type="text" name="product_name" class="form-control p-2 rounded-3" placeholder="ປ້ອນຊື່ສິນຄ້າ" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ນ້ຳໜັກ (Kg)</label>
                        <input type="number" step="0.01" name="weight" class="form-control p-2 rounded-3" placeholder="0.00" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted fw-semibold">ຈຳນວນ</label>
                        <input type="number" name="quantity" class="form-control p-2 rounded-3" placeholder="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-semibold">ປະເພດພັດສະດຸ</label>
                        <select name="category" class="form-select p-2 rounded-3" required>
                            <option value="">ເລືອກປະເພດ...</option>
                            <option value="ເຄື່ອງອຸປະໂພກ">ເຄື່ອງອຸປະໂພກ</option>
                            <option value="ເຄື່ອງບໍລິໂພກ">ເຄື່ອງບໍລິໂພກ</option>
                            <option value="ເຄື່ອງໃຊ້ໄຟຟ້າ">ເຄື່ອງໃຊ້ໄຟຟ້າ</option>
                            <option value="ເຄື່ອງກໍ່ສ້າງ">ເຄື່ອງກໍ່ສ້າງ</option>
                            <option value="ອຸປະກອນການແພດ">ອຸປະກອນການແພດ</option>
                            <option value="ເຄື່ອງຂຽນ">ເຄື່ອງຂຽນ</option>
                            <option value="ອື່ນໆ">ອື່ນໆ</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" name="add" class="btn btn-primary p-2 w-100 rounded-3 shadow-sm fw-semibold">
                            <i class="fa-solid fa-plus me-1"></i> ເພີ່ມສິນຄ້າ
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
                                <th>ຊື່ສິນຄ້າ</th>
                                <th>ນ້ຳໜັກ</th>
                                <th>ຈຳນວນໃນສາງ</th>
                                <th>ປະເພດ</th>
                                <th>ລາຍລະອຽດ</th>
                                <th class="text-center" width="20%">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // ກວດສອບການເຊື່ອມຕໍ່ຖານຂໍ້ມູນ
                            if (!$conn) {
                                echo "<tr><td colspan='7' class='text-center text-danger'>ບໍ່ສາມາດເຊື່ອມຕໍ່ຖານຂໍ້ມູນໄດ້</td></tr>";
                            } else {
                                $res = mysqli_query($conn, "SELECT * FROM product ORDER BY product_id DESC");
                                if (mysqli_num_rows($res) > 0) {
                                    while($row = mysqli_fetch_assoc($res)) {
                                        $productId = htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8');
                                        $productName = htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8');
                                        $weight = htmlspecialchars($row['weight'], ENT_QUOTES, 'UTF-8');
                                        $quantity = htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8');
                                        $category = htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8');
                                        $description = htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8');
                                        
                                        // ກຳນົດສີຂອງ badge ຕາມປະເພດ
                                        $categoryColors = [
                                            'ເຄື່ອງອຸປະໂພກ' => 'primary',
                                            'ເຄື່ອງບໍລິໂພກ' => 'success',
                                            'ເຄື່ອງໃຊ້ໄຟຟ້າ' => 'warning',
                                            'ເຄື່ອງກໍ່ສ້າງ' => 'danger',
                                            'ອຸປະກອນການແພດ' => 'info',
                                            'ເຄື່ອງຂຽນ' => 'secondary',
                                            'ອື່ນໆ' => 'dark'
                                        ];
                                        $color = isset($categoryColors[$category]) ? $categoryColors[$category] : 'secondary';
                                        
                                        echo "<tr>
                                            <td><span class='badge bg-secondary bg-opacity-10 text-secondary px-2 py-1.5 rounded'>#{$productId}</span></td>
                                            <td class='fw-semibold text-dark'>{$productName}</td>
                                            <td class='text-primary fw-bold'><i class='fa-solid fa-weight-scale me-1 small opacity-50'></i>" . number_format($weight, 2) . " Kg</td>
                                            <td><span class='badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-medium'><i class='fa-solid fa-cubes me-1 small'></i>{$quantity} ລາຍການ</span></td>
                                            <td><span class='badge bg-{$color} bg-opacity-10 text-{$color} category-badge'><i class='fa-solid fa-tag me-1'></i>{$category}</span></td>
                                            <td><span class='text-muted small'>" . ($description ? $description : '-') . "</span></td>
                                            <td class='text-center'>
                                                <button type='button' class='btn btn-warning btn-sm btn-action text-white me-1' onclick='editProduct({$productId}, \"{$productName}\", \"{$weight}\", \"{$quantity}\", \"{$category}\", \"{$description}\")'>
                                                    <i class='fa-solid fa-pen-to-square me-1'></i> ແກ້ໄຂ
                                                </button>
                                                <a href='product.php?delete={$productId}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"ທ່ານຕ້ອງການລຶບສິນຄ້ານີ້ແທ້ຫຼືບໍ່?\")'>
                                                    <i class='fa-solid fa-trash-can me-1'></i> ລຶບ
                                                </a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center text-muted py-4'>ຍັງບໍ່ມີຂໍ້ມູນສິນຄ້າ</td></tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal ແກ້ໄຂຂໍ້ມູນ -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-0 bg-light rounded-top-4">
        <h5 class="modal-title fw-bold" id="editModalLabel"><i class="fa-solid fa-box-open text-warning me-2"></i> ແກ້ໄຂຂໍ້ມູນສິນຄ້າ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
          <div class="modal-body p-4">
                <input type="hidden" name="product_id" id="edit_id">
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ຊື່ສິນຄ້າ</label>
                    <input type="text" name="product_name" id="edit_name" class="form-control p-2 rounded-3" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ນ້ຳໜັກ (Kg)</label>
                        <input type="number" step="0.01" name="weight" id="edit_weight" class="form-control p-2 rounded-3" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small text-muted">ຈຳນວນ</label>
                        <input type="number" name="quantity" id="edit_quantity" class="form-control p-2 rounded-3" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ປະເພດພັດສະດຸ</label>
                    <select name="category" id="edit_category" class="form-select p-2 rounded-3" required>
                        <option value="">ເລືອກປະເພດ...</option>
                        <option value="ເຄື່ອງອຸປະໂພກ">ເຄື່ອງອຸປະໂພກ</option>
                        <option value="ເຄື່ອງບໍລິໂພກ">ເຄື່ອງບໍລິໂພກ</option>
                        <option value="ເຄື່ອງໃຊ້ໄຟຟ້າ">ເຄື່ອງໃຊ້ໄຟຟ້າ</option>
                        <option value="ເຄື່ອງກໍ່ສ້າງ">ເຄື່ອງກໍ່ສ້າງ</option>
                        <option value="ອຸປະກອນການແພດ">ອຸປະກອນການແພດ</option>
                        <option value="ເຄື່ອງຂຽນ">ເຄື່ອງຂຽນ</option>
                        <option value="ອື່ນໆ">ອື່ນໆ</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ລາຍລະອຽດສິນຄ້າ</label>
                    <input type="text" name="description" id="edit_description" class="form-control p-2 rounded-3">
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
// ຟັງຊັນແກ້ໄຂຂໍ້ມູນ
function editProduct(id, name, weight, quantity, category, description) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_weight').value = weight;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_description').value = description;
    
    // ສັ່ງເປີດ Modal
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>
</body>
</html>