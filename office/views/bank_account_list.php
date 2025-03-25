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

$bank = $db->query("SELECT * FROM banks WHERE id=?", $id)->fetchArray();
$banks = $db->query("SELECT * FROM bank_accounts WHERE bank_id = ? AND deleted_at IS NULL ORDER BY display_name", $id)->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>บัญชีธนาคาร: <?php echo $bank['bank_name']; ?></title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard_owner.php">แดชบอร์ด</a></li>
                <li class="breadcrumb-item"><a href="bank_list.php">ธนาคาร</a></li>
                <li class="breadcrumb-item active" aria-current="page">บัญชีธนาคาร</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">รายการบัญชีธนาคาร<?php echo $bank['bank_name']; ?></h4>
            <a href="bank_account_create.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm">+ เพิ่มบัญชีใหม่</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                <tr>
                    <th style="text-align: center">#</th>
                    <th>ชื่อเรียก</th>
                    <th>ชื่อบัญชี</th>
                    <th>เลขที่บัญชี</th>
                    <th>สาขา</th>
                    <th>วันที่เปิดใช้</th>
                    <th style="width: 100px;">ธุรกรรม</th>
                    <th style="width: 100px;">สถานะ</th>
                    <th style="text-align: center; width: 80px;">จัดการ</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $i=1;
                    foreach ($banks as $b):
                ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $i; ?></td>
                        <td><?php echo htmlspecialchars($b['display_name']) ?></td>
                        <td><?php echo htmlspecialchars($b['account_name']) ?></td>
                        <td style="width: 150px;"><?php echo htmlspecialchars($b['account_number']) ?></td>
                        <td style="width: 120px;"><?php echo htmlspecialchars($b['branch']) ?></td>
                        <td style="width: 110px;"><?php echo $b['open_date'] ?></td>
                        <td style="text-align: right;"><?php echo $b['transaction_count'] ?></td>
                        <td><?php echo $b['status'] === 'active' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?></td>
                        <td style="text-align: right;">
                            <a href="bank_account_edit.php?id=<?php echo $b['id'] ?>" class="btn btn-sm btn-outline-secondary">แก้ไข</a>
                        </td>
                    </tr>
                <?php
                    $i++;
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>