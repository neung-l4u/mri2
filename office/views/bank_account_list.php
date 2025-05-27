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
    <link href="../assets/libs/bootstrap-5.3.3-dist/bootstrap-icons-1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
                <li class="breadcrumb-item active" aria-current="page">บัญชี <?php echo $bank['bank_name']; ?></li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="bi bi-journal-medical"></i> บัญชีธนาคาร <span class="text-primary"> <?php echo $bank['bank_name']; ?></span></h4>
            <a href="bank_account_create.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm">+ เพิ่มบัญชีใหม่</a>
        </div>

        <div class="table-responsive">
            <table id="tableData" class="table table-borderless table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th style="width: 30px;">#</th>
                    <th class="text-center" style="width: 120px;">ชื่อเรียก</th>
                    <th class="text-center">ชื่อบัญชี</th>
                    <th class="text-center" style="width: 200px;">เลขที่บัญชี</th>
                    <th class="text-end" style="width: 100px;">ธุรกรรม</th>
                    <th class="text-center" style="width: 80px;">สถานะ</th>
                    <th style="width: 80px;"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    if (count($banks)>0) {
                        $i=1;
                        foreach ($banks as $b):
                ?>
                    <tr>
                        <td class="text-end"><?php echo $i; ?></td>
                        <td class="text-start">
                            <i class="bi bi-upc-scan" title="รหัสบัญชี"></i>  <small class="text-secondary"><?php echo htmlspecialchars($b['display_name']); ?></small>
                        </td>
                        <td class="text-start"><?php echo htmlspecialchars($b['account_name']); ?></td>
                        <td class="text-start"><?php echo htmlspecialchars($b['account_number']); ?></td>
                        <td class="text-end"><?php echo number_format($b['transaction_count']); ?> <i class="bi bi-arrow-left-right"></i></td>
                        <td class="text-center"><?php echo $b['status'] === 'active' ? '<i class="bi bi-check text-success" title="เปิดใช้งาน"></i>' : '<i class="bi bi-x text-danger" title="ปิดใช้งาน"></i>'; ?></td>
                        <td class="text-end">
                            <a href="bank_account_edit.php?id=<?php echo $b['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill" title="แก้ไข"></i></a>
                        </td>
                    </tr>
                <?php
                    $i++;
                    endforeach;
                    }else{?>
                        <tr><td colspan="9" style="text-align: center">ยังไม่มีข้อมูล</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>