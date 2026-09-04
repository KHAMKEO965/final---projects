<?php
session_start();
if (!isset($_SESSION['user_id'])) 
include 'db.php';

if (isset($_POST['add'])) {
    $shipment_id = intval($_POST['shipment_id']);
    $doc_name = mysqli_real_escape_string($conn, $_POST['document_name']);
    $upload_date = mysqli_real_escape_string($conn, $_POST['upload_date']);
    
    $file_path = ""; 
    if(!empty($_FILES['file']['name'])) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $file_path = $target_dir . time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES["file"]["tmp_name"], $file_path);
    }
    mysqli_query($conn, "INSERT INTO document (shipment_id, document_name, file_path, upload_date) VALUES ('$shipment_id', '$doc_name', '$file_path', '$upload_date')");
    header("Location: document.php"); exit();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $file_query = mysqli_query($conn, "SELECT file_path FROM document WHERE document_id = $id");
    $file_data = mysqli_fetch_assoc($file_query);
    if (!empty($file_data['file_path']) && file_exists($file_data['file_path'])) { unlink($file_data['file_path']); }
    mysqli_query($conn, "DELETE FROM document WHERE document_id = $id");
    header("Location: document.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head><meta charset="UTF-8"><title>ຈັດການເອກະສານ</title></head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'navbar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h3 class="fw-bold text-dark mb-4"><i class="fa-solid fa-folder-open text-primary me-2"></i> ຈັດການເອກະສານຂົນສົ່ງ</h3>
                
                <form method="POST" enctype="multipart/form-data" class="row g-3 p-3 bg-light rounded-4">
                    <div class="col-md-3">
                        <select name="shipment_id" class="form-select border-0 shadow-sm p-2 rounded-3" required>
                            <option value="">-- ລະຫັດການສົ່ງ --</option>
                            <?php $sh = mysqli_query($conn, "SELECT s.shipment_id, c.customer_name FROM shipment s JOIN customer c ON s.customer_id = c.customer_id"); while($r = mysqli_fetch_assoc($sh)) echo "<option value='{$r['shipment_id']}'>ID: {$r['shipment_id']} - {$r['customer_name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="col-md-3"><input type="text" name="document_name" class="form-control border-0 shadow-sm p-2 rounded-3" placeholder="ຊື່ເອກະສານ (ເຊັ່ນ: Air Waybill)" required></div>
                    <div class="col-md-3"><input type="file" name="file" class="form-control border-0 shadow-sm p-2 rounded-3" required></div>
                    <div class="col-md-2"><input type="date" name="upload_date" class="form-control border-0 shadow-sm p-2 rounded-3" value="<?php echo date('Y-m-d'); ?>" required></div>
                    <div class="col-md-1"><button type="submit" name="add" class="btn btn-primary p-2 w-100 rounded-3 shadow-sm"><i class="fa-solid fa-upload"></i></button></div>
                </form>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr><th>ID</th><th>ລະຫັດສົ່ງ</th><th>ຊື່ເອກະສານ</th><th>ໄຟລ໌</th><th>ວັນອັບໂຫຼດ</th><th class="text-center">ຈັດການ</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM document ORDER BY document_id DESC");
                            while($row = mysqli_fetch_assoc($res)) {
                                echo "<tr>
                                    <td>{$row['document_id']}</td>
                                    <td class='fw-bold'>#SH-00{$row['shipment_id']}</td>
                                    <td><i class='fa-regular fa-file-pdf text-danger me-2 fs-5'></i>{$row['document_name']}</td>
                                    <td><a href='{$row['file_path']}' target='_blank' class='btn btn-sm btn-outline-primary rounded-3'><i class='fa-solid fa-eye me-1'></i> ເປີດເບິ່ງ</a></td>
                                    <td>" . date('d-m-Y', strtotime($row['upload_date'])) . "</td>
                                    <td class='text-center'>
                                        <a href='document.php?delete={$row['document_id']}' class='btn btn-danger btn-sm rounded-3 px-3' onclick='return confirm(\"ລຶບ?\")'>ລຶບ</a>
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
</body>
</html>