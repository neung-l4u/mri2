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

$sql .= " ORDER BY c.customer_code";
$customers = $db->query($sql, ...$params)->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ลูกค้าในสาย<?php echo $route ? ' ' . htmlspecialchars($route['route_name']) : '' ?></title>
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
                <li class="breadcrumb-item"><a href="main.php?p=lines">สายลูกค้า</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php echo $route ? htmlspecialchars($route['route_code'].': '.$route['route_name']) : '' ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="bi bi-person-vcard-fill"></i> ลูกค้าในสาย <span class="text-info"> <?php echo $route ? htmlspecialchars($route['route_code'].': '.$route['route_name']) : '' ?></span></h4>
            <a href="customer_create.php<?php echo $route ? '?route_id=' . $route['id'] : '' ?>" class="btn btn-primary btn-sm">+ เพิ่มลูกค้า</a>
        </div>

        <div class="table-responsive">
            <table id="tableData" class="table table-borderless table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th style="width: 50px;" class="text-center">#</th>
                    <th style="width: 100px;" class="text-center">รหัสลูกค้า</th>
                    <th>ชื่อลูกค้า</th>
                    <th style="width: 150px;" class="text-center">เบอร์โทร</th>
                    <th style="width: 120px;" class="text-start">เซลที่ดูแล</th>
                    <th style="width: 100px;" class="text-center">การชำระ</th>
                    <th style="width: 80px;" class="text-center">ค่าส่ง</th>
                    <th style="width: 80px;" class="text-center">สถานะ</th>
                    <th style="width: 120px;"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(count($customers) > 0) {
                    $i=1;
                    foreach ($customers as $c):
                        ?>
                        <tr>
                            <td class="text-end"><?php echo $i; ?></td>
                            <td>
                                <i class="bi bi-upc-scan" title="รหัสลูกค้า"></i>  <small class="text-secondary"><?php echo showText($c['customer_code']) ?></small>
                            </td>
                            <td class="text-start"><?php echo showText($c['name']) ?></td>
                            <td class="text-start"><i class="bi bi-telephone-fill"></i> <?php echo showText($c['phone']) ?></td>
                            <td><i class="bi bi-person-circle" title="เซล"></i> <?php echo showText($c['salesperson_name']) ?></td>
                            <td class="text-center"><?php echo showText($c['payment_type']) ?></td>
                            <td class="text-center"><?php echo $c['shipFee'] === '0' ? '<small class="text-secondary">ฟรี</small>':'<small class="text-primary">'.$c['shipFee'].' บาท</small>'; ?></td>
                            <td class="text-center"><?php echo $c['status'] === 'on' ? '<i class="bi bi-check text-success" title="เปิดใช้งาน"></i>' : '<i class="bi bi-x text-danger" title="ปิดใช้งาน"></i>' ?></td>
                            <td class="text-end pr-3">
                                <a href="customer_products.php?id=<?php echo $c['id'] ?>&route_id=<?php echo $route_id; ?>&customer_id=<?php echo $c['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box" title="สินค้าของลูกค้าคนนี้"></i></i></a>
                                <a href="customer_edit.php?id=<?php echo $c['id'] ?>&route_id=<?php echo $route_id; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill" title="แก้ไข"></i></a>
                            </td>
                        </tr>
                    <?php
                    $i++;
                    endforeach;
                    }else{ ?>
                    <tr><td colspan="10" style="text-align: center">-- ยังไม่มีลูกค้าในสายนี้ --</td></tr>
                <?php }//else ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

<?php
function showText($txt): string
{
    if (!empty($txt)) { return htmlspecialchars($txt); }
    else { return "-"; }
}//showText
?>