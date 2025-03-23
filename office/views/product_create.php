<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$categories = $db->query("SELECT id, name FROM product_categories WHERE deleted_at IS NULL AND status = 'on' ORDER BY name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เพิ่มสินค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="product_list.php">สินค้า</a></li>
                <li class="breadcrumb-item active" aria-current="page">เพิ่มสินค้าใหม่</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">เพิ่มสินค้าใหม่</h4>
        <form method="POST" action="product_store.php">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ชื่อสินค้า</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ประเภทสินค้า</label>
                    <select name="category_id" class="form-select" required>
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
                    <select name="status" class="form-select">
                        <option value="on" selected>เปิดใช้งาน</option>
                        <option value="off">ปิดใช้งาน</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">บันทึกสินค้า</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
