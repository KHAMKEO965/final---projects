<?php

include 'db.php';

// 1. เพิ่ม (int) เพื่อป้องกัน SQL Injection และสกัดกั้นข้อมูลที่ไม่ใช่ตัวเลข
$id = (int)($_GET['id'] ?? 1);

// ດືງຂໍ້ມູນການຂົນສົ່ງ
$res = mysqli_query($conn, "SELECT s.*, c.customer_name, c.phone, c.address, p.product_name, p.weight, f.flight_number 
                            FROM shipment s 
                            JOIN customer c ON s.customer_id = c.customer_id 
                            JOIN product p ON s.product_id = p.product_id 
                            JOIN flight f ON s.flight_id = f.flight_id 
                            WHERE s.shipment_id = $id");
$data = mysqli_fetch_assoc($res);

// 2. เพิ่ม payment_method ใน SELECT เพื่อให้ด้านล่างดึงไปแสดงผลได้
//*ແມ່ນ ຄຳສັ່ງສຳລັບສົ່ງຄຳສັ່ງຄົ້ນຫາ (Query) ໄປຍັງຖານຂໍ້ມູນ MySQL ເພື່ອດຶງຂໍ້ມູນຈຳນວນເງິນ (amount) ມາຈາກຕາຕະລາງ payment 
// ໂດຍອີງຕາມລະຫັດການຂົນສົ່ງ (shipment_id) ທີ່ົງກົງກັບຄ່າໃນຕົວແປ $id
$pay_res = mysqli_query($conn, "SELECT amount FROM payment WHERE shipment_id = $id");
$pay = mysqli_fetch_assoc($pay_res);

// ตรวจสอบว่ามีข้อมูลหรือไม่ ถ้าไม่มีให้กำหนดค่าเริ่มต้น
if (!$data) {
    $data = [
        'shipment_id' => $id,
        'customer_name' => 'ບໍ່ມີຂໍ້ມູນ',
        'phone' => '-',
        'address' => '-',
        'product_name' => 'ບໍ່ມີຂໍ້ມູນສິນຄ້າ',
        'weight' => 0,
        'flight_number' => '-',
        'status' => 'ບໍ່ລະບຸ',
        'shipment_date' => date('Y-m-d')
    ];
}

if (!$pay) {
    $pay = [
        'amount' => 0,
        'payment_method' => 'ຄ້າງຊຳລະ'
    ];
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>Invoice #SH-<?= $id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #fff; font-family: 'Noto Sans Lao', sans-serif; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
<div class="container my-5 p-4 border rounded-4 shadow-sm" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-3">
        <div>
            <h2 class="fw-bold text-primary">LAO AIR CARGO</h2>
            <p class="text-muted m-0">ທ່າອາກາດສະຍານສາກົນວັດໄຕ, ນະຄອນຫຼວງວຽງຈັນ</p>
        </div>
        <div class="text-end">
            <h4 class="fw-bold text-dark m-0">ໃບບິນຂົນສົ່ງ (INVOICE)</h4>
            <span class="text-primary fw-bold">#SH-<?= $data['shipment_id'] ?></span>
            <p class="text-muted m-0">ວັນທີ: <?= date('d-m-Y', strtotime($data['shipment_date'])) ?></p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-6">
            <h6 class="fw-bold text-muted text-uppercase small">ສົ່ງເຖິງ (Customer):</h6>
            <h5 class="fw-bold text-dark m-0"><?= $data['customer_name'] ?></h5>
            <p class="text-muted m-0">ເບີໂທ: <?= $data['phone'] ?></p>
            <p class="text-muted m-0">ທີ່ຢູ່: <?= $data['address'] ?></p>
        </div>
        <div class="col-6 text-end">
            <h6 class="fw-bold text-muted text-uppercase small">ລາຍລະອຽດຖ້ຽວບິນ:</h6>
            <h5 class="fw-bold text-primary m-0">Flight No: <?= $data['flight_number'] ?></h5>
            <p class="text-muted m-0">ສະຖານະ: <?= $data['status'] ?></p>
        </div>
    </div>

    <table class="table table-bordered align-middle mb-4">
        <thead class="table-light">
            <tr>
                <th>ລາຍການສິນຄ້າ</th>
                <th class="text-end" style="width: 150px;">ນ້ຳໜັກ (KG)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><h6 class="fw-bold m-0"><?= $data['product_name'] ?></h6></td>
                <td class="text-end fw-bold">
                    <?php 
                    // ตรวจสอบว่าน้ำหนักมีค่าและไม่ใช่ null หรือ空
                    $weight = isset($data['weight']) && $data['weight'] !== '' ? (float)$data['weight'] : 0;
                    echo number_format($weight, 2) . ' KG';
                    ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="row justify-content-end mb-5">
        <div class="col-5 text-end">
            <div class="d-flex justify-content-between border-bottom py-2">
            </div>
            <div class="d-flex justify-content-between py-2">
                <span class="h5 fw-bold text-dark">ຍອດລວມທັງໝົດ:</span>
                <span class="h5 fw-bold text-success"><?= number_format($pay['amount']) ?> ₭</span>
            </div>
        </div>
    </div>

    <div class="text-center no-print mt-4">
        <hr>
        <button class="btn btn-primary rounded-3 px-4" onclick="window.print()">
            <i class="fa-solid fa-print me-2"></i>ພິມບິນນີ້
        </button>
        <a href="shipment.php" class="btn btn-light rounded-3 px-4 ms-2">ກັບຄືນ</a>
    </div>
</div>

</body>
</html>