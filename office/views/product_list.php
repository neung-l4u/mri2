<?php
session_start();
$myID = $_SESSION['user_id'];

require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$productsDown = $db->query("SELECT p.*, c.name AS category_name, p.unitCalculate 
                        FROM products p
                        LEFT JOIN product_categories c ON p.category_id = c.id
                        WHERE p.deleted_at IS NULL AND p.productLevel = 'down'
                        ORDER BY c.name, p.name")->fetchAll();

$productsUp = $db->query("SELECT p.*, c.name AS category_name, p.unitCalculate 
                        FROM products p
                        LEFT JOIN product_categories c ON p.category_id = c.id
                        WHERE p.deleted_at IS NULL AND p.productLevel = 'up'
                        ORDER BY c.name, p.name")->fetchAll();

$categories = $db->query("SELECT id, name FROM product_categories WHERE deleted_at IS NULL AND status = 'on' ORDER BY name")->fetchAll();

?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการสินค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="main.php?p=dashboard">
                        <svg class="nav-icon mr-2" height="1.2em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        แดชบอร์ด
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">สินค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="bi bi-arrow-down-square-fill"></i> รายการสินค้า (ตลาดล่าง)</h4>
            <a href="product_create.php?lvl=d" class="btn btn-primary btn-sm">+ เพิ่มสินค้าใหม่</a>
<!--            <button id="btnModal" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">-->
<!--                -->
<!--                + เพิ่มสินค้า-->
<!--            </button>-->
        </div>

        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th style="width: 50px;" class="text-center">#</th>
                    <th class="text-center">ประเภท: ชื่อสินค้า</th>
                    <th style="width: 150px;" class="text-end">ขนาด</th>
                    <th style="width: 80px;" class="text-center">หน่วย</th>
                    <th style="width: 150px;" class="text-end">ราคาตั้งต้น (บาท)</th>
                    <th style="width: 80px;" class="text-center">สถานะ</th>
                    <th style="width: 80px;"></th>
                </thead>
                <tbody>
                <?php
                if(count($productsDown)>0){
                    $i=1;
                    foreach ($productsDown as $p): ?>
                    <tr>
                        <td class="text-end"><?php echo $i; ?></td>
                        <td><?php echo '<small class="text-secondary">'.htmlspecialchars($p['category_name']).'</small>: '.htmlspecialchars($p['name']); ?></td>
                        <td class="text-end"><?php echo number_format($p['package_size_grams']) ?> กรัม</td>
                        <td class="text-center"><?php echo $p['unitCalculate'] === 'weight' ? 'กิโล' : 'ถุง'; ?></td>
                        <td class="text-end">฿<?php echo number_format($p['default_price_per_pack'], 2) ?></td>
                        <td class="text-center"><?php echo $p['status'] === 'on' ? '<i class="bi bi-check text-success" title="เปิดใช้งาน"></i>' : '<i class="bi bi-x text-danger" title="ปิดใช้งาน"></i>' ?></td>
                        <td class="text-end pr-3">
                            <a href="product_edit.php?id=<?php echo $p['id'] ?>"  class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill" title="แก้ไข"></i></a>
                        </td>
                    </tr>
                <?php
                        $i++;
                        endforeach;
                } else{?>
                <tr>
                    <td colspan="7" class="text-center text-danger">ไม่มีข้อมูล</td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow-sm mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="bi bi-arrow-up-square-fill"></i> รายการสินค้า (ตลาดบน)</h4>
            <a href="product_create.php?lvl=u" class="btn btn-primary btn-sm">+ เพิ่มสินค้าใหม่</a>
            <!--            <button id="btnModal" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">-->
            <!--                -->
            <!--                + เพิ่มสินค้า-->
            <!--            </button>-->
        </div>

        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th style="width: 50px;" class="text-center">#</th>
                    <th class="text-center">ประเภท: ชื่อสินค้า</th>
                    <th style="width: 150px;" class="text-end">ขนาด</th>
                    <th style="width: 80px;" class="text-center">หน่วย</th>
                    <th style="width: 150px;" class="text-end">ราคาตั้งต้น (บาท)</th>
                    <th style="width: 80px;" class="text-center">สถานะ</th>
                    <th style="width: 80px;"></th>
                </thead>
                <tbody>
                <?php
                if(count($productsUp)>0){
                    $i=1;
                    foreach ($productsUp as $u): ?>
                        <tr>
                            <td class="text-end"><?php echo $i; ?></td>
                            <td><?php echo '<small class="text-secondary">'.htmlspecialchars($u['category_name']).'</small>: '.htmlspecialchars($u['name']); ?></td>
                            <td class="text-end"><?php echo number_format($u['package_size_grams']) ?> กรัม</td>
                            <td class="text-center"><?php echo $u['unitCalculate'] === 'weight' ? 'กิโล' : 'ถุง'; ?></td>
                            <td class="text-end">฿<?php echo number_format($u['default_price_per_pack'], 2) ?></td>
                            <td class="text-center"><?php echo $u['status'] === 'on' ? '<i class="bi bi-check text-success" title="เปิดใช้งาน"></i>' : '<i class="bi bi-x text-danger" title="ปิดใช้งาน"></i>' ?></td>
                            <td class="text-end pr-3">
                                <a href="product_edit.php?id=<?php echo $u['id'] ?>"  class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill" title="แก้ไข"></i></a>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                } else{?>
                    <tr>
                        <td colspan="7" class="text-center text-danger">ไม่มีข้อมูล</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
