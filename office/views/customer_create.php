<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$route_id = $_GET['route_id'] ?? '';
if ($route_id) {
    $route = $db->query("SELECT * FROM customer_routes WHERE id = ? AND deleted_at IS NULL", $route_id)->fetchArray();
}
$banks = $db->query("SELECT * FROM bank_accounts WHERE deleted_at IS NULL AND status = 'active' ORDER BY display_name")->fetchAll();
$salespersons = $db->query("SELECT DISTINCT u.salesperson_id, u.name FROM users u WHERE u.role = 'sales' AND u.salesperson_id IS NOT NULL AND u.deleted_at IS NULL AND u.status = 'on' ORDER BY u.name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เพิ่มลูกค้า</title>
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
                <li class="breadcrumb-item"><a href="main.php?p=lines">สายลูกค้า</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="customer_list.php?route_id=<?php echo $route_id; ?>">ลูกค้า<?php echo $route ? 'ในสาย ' . htmlspecialchars($route['route_name']) : '' ?></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">เพิ่มลูกค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">เพิ่มลูกค้าใหม่</h4>
        <form method="POST" action="customer_store.php">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">รหัสลูกค้า</label>
                    <input type="text" name="customer_code" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">ชื่อลูกค้า</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ชื่อเล่น</label>
                    <input type="text" name="nickname" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">เบอร์โทร</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="col-md-12">
                    <label class="form-label">ที่อยู่</label>
                    <textarea name="address" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">สายลูกค้า</label>
                    <select name="route_id" class="form-select" required>
                        <option value="">-- เลือกสาย --</option>
                        <?php foreach ($routes as $r): ?>
                            <option value="<?php echo $r['id'] ?>" <?php echo $r['id'] == $route_id ? 'selected' : '' ?>><?php echo $r['route_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">เซลที่ดูแล</label>
                    <select name="salesperson_id" class="form-select">
                        <option value="">-- เลือกเซล --</option>
                        <?php foreach ($salespersons as $s): ?>
                            <option value="<?php echo $s['salesperson_id'] ?>"><?php echo $s['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">การชำระเงิน</label>
                    <select name="payment_type" class="form-select" required>
                        <option value="โอน">โอน</option>
                        <option value="เงินสด">เงินสด</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">บัญชีที่ใช้รับโอน</label>
                    <select name="bank_account_id" class="form-select">
                        <option value="">-- เลือกบัญชี --</option>
                        <?php foreach ($banks as $b): ?>
                            <option value="<?php echo $b['id'] ?>"><?php echo $b['display_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ประเภทลูกค้า</label>
                    <select name="vat_type" class="form-select">
                        <option value="no_vat">ไม่มี VAT</option>
                        <option value="vat">มี VAT</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">สถานะ</label>
                    <select name="status" class="form-select">
                        <option value="on" selected>เปิดใช้งาน</option>
                        <option value="off">ปิดใช้งาน</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">บันทึกลูกค้า</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>