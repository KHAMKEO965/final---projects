<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // header("Location: login.php"); exit(); 
}

include 'db.php';

// ກວດສອບຊື່ຄອລຳທີ່ມີຢູ່
$columns = mysqli_query($conn, "SHOW COLUMNS FROM package_type");
$column_names = [];
while ($col = mysqli_fetch_assoc($columns)) {
    $column_names[] = $col['Field'];
}

// ກຳນົດຊື່ຄອລຳທີ່ຖືກຕ້ອງ
$name_column = 'package_type_name'; // ຄ່າເລີ່ມຕົ້ນ
$id_column = 'package_type_id'; // ຄ່າເລີ່ມຕົ້ນ

// ກວດສອບຊື່ຄອລຳຊື່
if (in_array('type_name', $column_names)) {
    $name_column = 'type_name';
} elseif (in_array('name', $column_names)) {
    $name_column = 'name';
} elseif (in_array('package_type', $column_names)) {
    $name_column = 'package_type';
} elseif (in_array('type', $column_names)) {
    $name_column = 'type';
}

// ກວດສອບຊື່ຄອລຳ ID
if (in_array('id', $column_names)) {
    $id_column = 'id';
} elseif (in_array('type_id', $column_names)) {
    $id_column = 'type_id';
} elseif (in_array('package_id', $column_names)) {
    $id_column = 'package_id';
}

// 1. CREATE: ເພີ່ມຂໍ້ມູນປະເພດພັດສະດຸ
if (isset($_POST['add'])) {
    $type_name = mysqli_real_escape_string($conn, $_POST['package_type_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $sql = "INSERT INTO package_type ($name_column, description) VALUES ('$type_name', '$description')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "ເພີ່ມຂໍ້ມູນສຳເລັດແລ້ວ";
    } else {
        $_SESSION['error'] = "ບໍ່ສາມາດເພີ່ມຂໍ້ມູນໄດ້: " . mysqli_error($conn);
    }
    header("Location: package_type.php");
    exit();
}

// 2. UPDATE: ແກ້ໄຂຂໍ້ມູນປະເພດພັດສະດຸ
if (isset($_POST['update'])) {
    $id = intval($_POST['package_type_id']);
    $type_name = mysqli_real_escape_string($conn, $_POST['package_type_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $sql = "UPDATE package_type SET $name_column='$type_name', description='$description' WHERE $id_column=$id";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "ອັບເດດຂໍ້ມູນສຳເລັດແລ້ວ";
    } else {
        $_SESSION['error'] = "ບໍ່ສາມາດອັບເດດຂໍ້ມູນໄດ້: " . mysqli_error($conn);
    }
    header("Location: package_type.php");
    exit();
}

// 3. DELETE: ລຶບຂໍ້ມູນປະເພດພັດສະດຸ
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM package_type WHERE $id_column = $id");
    header("Location: package_type.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຈັດການປະເພດພັດສະດຸ</title>
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
            
            <!-- ສະແດງຂໍ້ຄວາມແຈ້ງເຕືອນ -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i> <?php echo $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="card p-4 mb-4 bg-white">
                <h3 class="fw-bold text-dark mb-4">
                    <i class="fa-solid fa-boxes-packing text-primary me-2"></i> ຈັດການປະເພດພັດສະດຸ (Package Type)
                </h3>
                
                <form method="POST" class="row g-3 p-3 bg-light rounded-4">
                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-semibold">ຊື່ປະເພດພັດສະດຸ / ລັກສະນະຫໍ່</label>
                        <input type="text" name="package_type_name" class="form-control p-2 rounded-3" placeholder="ຕົວຢ່າງ: ກ່ອງກະດາດ, ພາເລດ, ຖັງ" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-semibold">ອະທິບາຍເພີ່ມເຕີມ</label>
                        <input type="text" name="description" class="form-control p-2 rounded-3" placeholder="ລະບຸລາຍລະອຽດ ຫຼື ເງື່ອນໄຂການຫຸ້ມຫໍ່ (ຖ້າມີ)">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" name="add" class="btn btn-primary p-2 w-100 rounded-3 shadow-sm fw-semibold">
                            <i class="fa-solid fa-plus me-1"></i> ເພີ່ມປະເພດ
                        </button>
                    </div>
                </form>
            </div>

            <div class="card p-3 bg-white">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="10%">ID</th>
                                <th width="30%">ປະເພດພັດສະດຸ</th>
                                <th width="42%">ລາຍລະອຽດອະທິບາຍ</th>
                                <th class="text-center" width="18%">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM package_type";
                            $res = mysqli_query($conn, $query);
                            
                            if ($res && mysqli_num_rows($res) > 0) {
                                while($row = mysqli_fetch_assoc($res)) {
                                    // ແກ້ໄຂການດຶງຄ່າ ID ແບບເຄື່ອນໄຫວ
                                    $id_value = isset($row[$id_column]) ? $row[$id_column] : (isset($row['package_type_id']) ? $row['package_type_id'] : (isset($row['id']) ? $row['id'] : 0));
                                    $name_value = isset($row[$name_column]) ? $row[$name_column] : (isset($row['package_type_name']) ? $row['package_type_name'] : (isset($row['type_name']) ? $row['type_name'] : (isset($row['name']) ? $row['name'] : 'N/A')));
                                    
                                    // ສ້າງ JSON ສຳລັບ JavaScript
                                    $jsonData = [
                                        'id' => $id_value,
                                        'name' => $name_value,
                                        'description' => isset($row['description']) ? $row['description'] : ''
                                    ];
                                    $jsonDataEncoded = json_encode($jsonData, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                                    
                                    echo "<tr>
                                        <td><span class='badge bg-secondary bg-opacity-10 text-secondary px-2 py-1.5 rounded'>#PK-{$id_value}</span></td>
                                        <td><strong class='text-dark'><i class='fa-solid fa-box text-muted me-2 small'></i>{$name_value}</strong></td>
                                        <td><span class='text-secondary small'>" . (isset($row['description']) && $row['description'] ? $row['description'] : '<em class="text-muted">- ບໍ່ມີລາຍລະອຽດ -</em>') . "</span></td>
                                        <td class='text-center'>
                                            <button type='button' class='btn btn-warning btn-sm btn-action text-white me-1' onclick='editPackageType({$jsonDataEncoded})'>
                                                <i class='fa-solid fa-pen-to-square'></i> ແກ້ໄຂ
                                            </button>
                                            <a href='package_type.php?delete={$id_value}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"ທ່ານຕ້ອງການລຶບປະເພດພັດສະດຸນີ້ແທ້ຫຼືບໍ່?\")'>
                                                <i class='fa-solid fa-trash-can'></i>
                                            </a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center py-4 text-muted'>❌ ບໍ່ມີຂໍ້ມູນປະເພດພັດສະດຸໃນລະບົບ</td></tr>";
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
        <h5 class="modal-title fw-bold" id="editModalLabel"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> ແກ້ໄຂຂໍ້ມູນປະເພດພັດສະດຸ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
          <div class="modal-body p-4">
                <input type="hidden" name="package_type_id" id="edit_id">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ຊື່ປະເພດພັດສະດຸ / ລັກສະນະຫໍ່</label>
                    <input type="text" name="package_type_name" id="edit_name" class="form-control p-2 rounded-3" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">ອະທິບາຍເພີ່ມເຕີມ</label>
                    <textarea name="description" id="edit_description" class="form-control p-2 rounded-3" rows="3"></textarea>
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
function editPackageType(data) {
    document.getElementById('edit_id').value = data.id || data.package_type_id || 0;
    document.getElementById('edit_name').value = data.name || data.package_type_name || data.type_name || '';
    document.getElementById('edit_description').value = data.description || '';
    
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>
</body>
</html>