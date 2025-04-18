<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$products = $db->query("SELECT p.*, c.name AS category_name
                        FROM products p
                        LEFT JOIN product_categories c ON p.category_id = c.id
                        WHERE p.deleted_at IS NULL
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
                <li class="breadcrumb-item"><a href="dashboard_owner.php">แดชบอร์ด</a></li>
                <li class="breadcrumb-item active" aria-current="page">สินค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">รายการสินค้า</h4>
<!--            <a href="product_create.php" class="btn btn-primary btn-sm">+ เพิ่มสินค้าใหม่</a>-->
            <button id="btnModal" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">
                <!-- svg -->
                + เพิ่มสินค้า (New)
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                <tr>
                    <th>ชื่อสินค้า</th>
                    <th>ประเภท</th>
                    <th>ขนาดบรรจุ (กรัม)</th>
                    <th class="text-end">ราคาตั้งต้น (บาท)</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['name']) ?></td>
                        <td><?php echo htmlspecialchars($p['category_name']) ?></td>
                        <td><?php echo number_format($p['package_size_grams']) ?></td>
                        <td class="text-end">฿<?php echo number_format($p['default_price_per_pack'], 2) ?></td>
                        <td><?php echo $p['status'] === 'on' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?></td>
<!--                        <td><a href="product_edit.php?id=--><?php //echo $p['id'] ?><!--"  class="btn btn-sm btn-outline-secondary">แก้ไข</a></td>-->
                        <td><a href="#" onclick="setEdit('<?php echo $p["id"]; ?>')" class="btn btn-sm btn-outline-secondary">แก้ไข</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalLabel">เพิ่มสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="d-flex flex-column">

                            <form method="POST" action="product_store.php">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">ชื่อสินค้า</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">ประเภทสินค้า</label>
                                        <select id="category_id" name="category_id" class="form-select" required>
                                            <option value="">-- เลือกประเภท --</option>
                                            <?php foreach ($categories as $c): ?>
                                                <option value="<?php echo $c['id'] ?>"><?php echo $c['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">ขนาดบรรจุ (กรัม)</label>
                                        <input type="number" name="package_size_grams" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">ราคาตั้งต้นต่อถุง (บาท)</label>
                                        <input type="number" name="default_price_per_pack" class="form-control" step="0.01" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">สถานะ</label>
                                        <select id="status" name="status" class="form-select">
                                            <option value="on" selected>เปิดใช้งาน</option>
                                            <option value="off">ปิดใช้งาน</option>
                                        </select>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-success" onclick="formSave()">บันทึกสินค้า</button>
                                    </div>
                                    <input type="text" name="formAction" id="formAction" value="add">
                                    <input type="text" name="editID" id="editID" value="<?php echo $_SESSION['user_id'];?>">
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- modal-body -->
            </div>
        </div>
    </div>
</body>
</html>
