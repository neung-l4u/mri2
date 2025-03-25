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
$routes = $db->query("SELECT id, route_name FROM customer_routes WHERE deleted_at IS NULL AND status = 'on' ORDER BY route_name")->fetchAll();
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
                <li class="breadcrumb-item"><a href="route_list.php">สายลูกค้า</a></li>
                <li class="breadcrumb-item"><a href="customer_list.php?route_id=<?php echo $route_id ?>">ลูกค้า</a></li>
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