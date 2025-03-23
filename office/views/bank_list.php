<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$banks = $db->query("SELECT * FROM bank_accounts WHERE deleted_at IS NULL ORDER BY display_name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการบัญชีธนาคาร</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard_owner.php">แดชบอร์ด</a></li>
                <li class="breadcrumb-item active" aria-current="page">บัญชีธนาคาร</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">รายการบัญชีธนาคาร</h4>
            <a href="bank_create.php" class="btn btn-primary btn-sm">+ เพิ่มบัญชีใหม่</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                <tr>
                    <th>ชื่อเรียก</th>
                    <th>ชื่อบัญชี</th>
                    <th>เลขที่บัญชี</th>
                    <th>ธนาคาร</th>
                    <th>สาขา</th>
                    <th>วันที่เปิดใช้</th>
                    <th>ธุรกรรม</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($banks as $b): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($b['display_name']) ?></td>
                        <td><?php echo htmlspecialchars($b['account_name']) ?></td>
                        <td><?php echo htmlspecialchars($b['account_number']) ?></td>
                        <td><?php echo htmlspecialchars($b['bank_name']) ?></td>
                        <td><?php echo htmlspecialchars($b['branch']) ?></td>
                        <td><?php echo $b['opened_at'] ?></td>
                        <td class="text-center"><?php echo $b['transaction_count'] ?></td>
                        <td><?php echo $b['status'] === 'active' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-secondary">แก้ไข</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>