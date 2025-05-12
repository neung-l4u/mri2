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

$bank = $db->query("SELECT * FROM bank_accounts WHERE id = ? AND deleted_at IS NULL", $id)->fetchArray();
$bankMain = $db->query("SELECT * FROM banks WHERE id=?", $bank['bank_id'])->fetchArray();
if (!$bank) {
    exit('ไม่พบข้อมูลบัญชีธนาคาร');
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขบัญชีธนาคาร: <?php echo htmlspecialchars($bank['display_name']) ?></title>
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
                <li class="breadcrumb-item"><a href="main.php?p=banks">ธนาคาร</a></li>
                <li class="breadcrumb-item active" aria-current="page">แก้ไขบัญชี</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">แก้ไขข้อมูลบัญชี<?php echo htmlspecialchars($bank['display_name']) ?></h4>
        <form method="POST" action="bank_account_update.php">
            <input type="hidden" name="id" value="<?php echo $bank['id'] ?>">
            <input type="hidden" name="bank_id" value="<?php echo $bank['bank_id'] ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ชื่อเรียกบัญชี</label>
                    <input type="text" name="display_name" class="form-control" value="<?php echo htmlspecialchars($bank['display_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ชื่อบัญชี</label>
                    <input type="text" name="account_name" class="form-control" value="<?php echo htmlspecialchars($bank['account_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">เลขที่บัญชี</label>
                    <input type="text" name="account_number" class="form-control" value="<?php echo htmlspecialchars($bank['account_number']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ธนาคาร</label>
                    <input type="text" class="form-control" value="<?php echo $bankMain['bank_name']; ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">สาขา</label>
                    <input type="text" name="branch" class="form-control" value="<?php echo htmlspecialchars($bank['branch']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">วันที่เปิดใช้</label>
                    <input type="date" name="opened_at" class="form-control" value="<?php echo $bank['open_date'] ?>">
                </div>
                <div class="col-md-6">
                    <label for="transaction_count" class="form-label">จำนวนธุรกรรม</label>
                    <input id="transaction_count" type="number" step="1" min="0" name="transaction_count" class="form-control" value="<?php echo $bank['transaction_count'] ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">สถานะ</label>
                    <select name="status" class="form-select">
                        <option value="active" <?php echo $bank['status'] === 'active' ? 'selected' : '' ?>>เปิดใช้งาน</option>
                        <option value="inactive" <?php echo $bank['status'] === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
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
