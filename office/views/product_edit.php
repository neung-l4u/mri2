<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$product = $db->query("SELECT * FROM products WHERE id = ? AND deleted_at IS NULL", $id)->fetchArray();
if (!$product) {
    exit('ไม่พบข้อมูลสินค้า');
}



$categories = $db->query("SELECT id, name FROM product_categories WHERE deleted_at IS NULL AND status = 'on' ORDER BY name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขสินค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
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
                <li class="breadcrumb-item"><a href="main.php?p=products">สินค้า</a></li>
                <li class="breadcrumb-item active" aria-current="page">แก้ไขสินค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">แก้ไขข้อมูลสินค้า</h4>
        <form method="POST" action="product_update.php">
            <input type="hidden" name="id" value="<?php echo $product['id'] ?>">
            <div class="col-md-12">
                <label for="productLevel" class="form-label">ระดับตลาด : </label>
                <select name="productLevel" id="productLevel">
                    <option value="down" <?php echo ($product['productLevel']=='down')?'selected':''; ?>>ตลาดล่าง</option>
                    <option value="up" <?php echo ($product['productLevel']=='up')?'selected':''; ?>>ตลาดบน</option>
                </select>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="productName" class="form-label">ชื่อสินค้า</label>
                    <input id="productName" type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ประเภทสินค้า</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?php echo $c['id'] ?>" <?php echo $product['category_id'] == $c['id'] ? 'selected' : '' ?>>
                                <?php echo $c['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="unitCalculate" class="form-label">หน่วยการคำนวณ</label>
                    <select class="form-select" id="unitCalculate" name="unitCalculate" required>
                        <option value="weight" <?php echo ($product['unitCalculate'] == 'weight')?"selected":""; ?> >คำนวณตามน้ำหนัก (กก.)</option>
                        <option value="piece" <?php echo ($product['unitCalculate'] == 'piece')?"selected":""; ?>>คำนวณตามจำนวน (ชิ้น)</option>
                    </select>
                    <div class="form-text">
                        <?php if ($level == 'down') {
                            echo 'สินค้า <strong>ตลาดล่าง</strong> ควรเลือกแบบน้ำหนัก';
                        } else {
                            echo 'สินค้า <strong>ตลาดบน</strong> ควรเลือกแบบจำนวนชิ้น';
                        } ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ขนาดบรรจุ (กรัม)</label>
                    <input type="number" name="package_size_grams" class="form-control" value="<?php echo $product['package_size_grams'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ราคาตั้งต้นต่อถุง (บาท)</label>
                    <input type="number" name="default_price_per_pack" class="form-control" step="0.01" value="<?php echo $product['default_price_per_pack'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">สถานะ</label>
                    <select name="status" class="form-select">
                        <option value="on" <?php echo $product['status'] === 'on' ? 'selected' : '' ?>>เปิดใช้งาน</option>
                        <option value="off" <?php echo $product['status'] === 'off' ? 'selected' : '' ?>>ปิดใช้งาน</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">บันทึกการแก้ไข</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
