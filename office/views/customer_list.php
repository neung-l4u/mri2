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
$route = null;
if ($route_id) {
    $route = $db->query("SELECT * FROM customer_routes WHERE id = ? AND deleted_at IS NULL", $route_id)->fetchArray();
}

$sql = "SELECT c.*, r.route_name, u.name AS salesperson_name, b.display_name AS bank_name
        FROM customers c
        LEFT JOIN customer_routes r ON c.route_id = r.id
        LEFT JOIN users u ON c.salesperson_id = u.salesperson_id
        LEFT JOIN bank_accounts b ON c.bank_account_id = b.id
        WHERE c.deleted_at IS NULL";
$params = [];

if ($route_id !== '') {
    $sql .= " AND c.route_id = ?";
    $params[] = $route_id;
}

$sql .= " ORDER BY c.name";
$customers = $db->query($sql, ...$params)->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ลูกค้าในสาย<?php echo $route ? ' ' . htmlspecialchars($route['route_name']) : '' ?></title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="route_list.php">สายลูกค้า</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    ลูกค้า<?php echo $route ? 'ในสาย ' . htmlspecialchars($route['route_name']) : '' ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">รายชื่อลูกค้า<?php echo $route ? 'ในสาย ' . htmlspecialchars($route['route_name']) : '' ?></h4>
            <a href="customer_create.php<?php echo $route ? '?route_id=' . $route['id'] : '' ?>" class="btn btn-primary btn-sm">+ เพิ่มลูกค้า</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                <tr>
                    <th>รหัสลูกค้า</th>
                    <th>ชื่อลูกค้า</th>
                    <th>เบอร์โทร</th>
                    <th>สาย</th>
                    <th>เซลที่ดูแล</th>
                    <th>การชำระ</th>
                    <th>บัญชีรับโอน</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(count($customers) > 0) {
                    foreach ($customers as $c):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['customer_code']) ?></td>
                            <td><?php echo htmlspecialchars($c['name']) ?></td>
                            <td><?php echo htmlspecialchars($c['phone']) ?></td>
                            <td><?php echo htmlspecialchars($c['route_name']) ?></td>
                            <td><?php echo htmlspecialchars($c['salesperson_name']) ?></td>
                            <td><?php echo htmlspecialchars($c['payment_type']) ?></td>
                            <td><?php echo htmlspecialchars($c['bank_name']) ?></td>
                            <td><?php echo $c['status'] === 'on' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?></td>
                            <td>
                                <a href="customer_edit.php?id=<?php echo $c['id'] ?>" class="btn btn-sm btn-outline-secondary">แก้ไข</a>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    }else{ ?>
                    <tr><td colspan="9" style="text-align: center">-- ยังไม่มีลูกค้าในสายนี้ --</td></tr>
                <?php }//else ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
