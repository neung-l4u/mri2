<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$route_id = $_GET['route_id'] ?? '';
$customer_id = $_GET['customer_id'] ?? '';

$routes = $db->query("SELECT id, route_name FROM customer_routes WHERE deleted_at IS NULL AND status = 'on' ORDER BY route_name")->fetchAll();
$customers = [];
if ($route_id !== '') {
    $customers = $db->query("SELECT id, name FROM customers WHERE route_id = ? AND deleted_at IS NULL ORDER BY name", $route_id)->fetchAll();
}

$sql = "SELECT s.withdrawal_date, c.customer_code, c.name AS customer_name, r.route_name,
               SUM(si.qty) AS total_kg, SUM(si.total_price) AS total_amount
        FROM sales_orders s
        JOIN customers c ON s.customer_id = c.id
        JOIN customer_routes r ON c.route_id = r.id
        JOIN sales_order_items si ON s.id = si.sales_order_id
        WHERE s.deleted_at IS NULL";
$params = [];

if ($from_date !== '') {
    $sql .= " AND s.withdrawal_date >= ?";
    $params[] = $from_date;
}
if ($to_date !== '') {
    $sql .= " AND s.withdrawal_date <= ?";
    $params[] = $to_date;
}
if ($route_id !== '') {
    $sql .= " AND c.route_id = ?";
    $params[] = $route_id;
}
if ($customer_id !== '') {
    $sql .= " AND c.id = ?";
    $params[] = $customer_id;
}

$sql .= " GROUP BY s.withdrawal_date, c.id ORDER BY s.withdrawal_date DESC";
$sales = $db->query($sql, ...$params)->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายงานยอดขาย</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/libs/select2/css/select2.min.css" rel="stylesheet">
    <link href="../assets/libs/select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="../assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
    <script src="../assets/libs/jQuery-v3.7.1/jquery-3.7.1.min.js"></script>
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
                <li class="breadcrumb-item active" aria-current="page">รายงานยอดขาย</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">รายงานยอดขาย</h4>
        <form method="GET" class="row g-3 mb-4" id="filterForm">
            <div class="col-md-3">
                <label class="form-label">วันที่เริ่มต้น</label>
                <input type="text" name="from_date" id="from_date" value="<?php echo $from_date ?>" class="form-control flatpickr" placeholder="ปปปป-ดด-วว" onchange="submitForm()">
            </div>
            <div class="col-md-3">
                <label class="form-label">ถึงวันที่</label>
                <input type="text" name="to_date" id="to_date" value="<?php echo $to_date ?>" class="form-control flatpickr" placeholder="ปปปป-ดด-วว" onchange="submitForm()">
            </div>
            <div class="col-md-3">
                <label class="form-label">สายลูกค้า</label>
                <select name="route_id" id="route_id" class="form-select" onchange="submitForm()">
                    <option value="">-- ทุกสาย --</option>
                    <?php foreach ($routes as $r): ?>
                        <option value="<?php echo $r['id'] ?>" <?php if ($r['id'] == $route_id) echo 'selected'; ?>>
                            <?php echo $r['route_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">ลูกค้า</label>
                <select name="customer_id" id="customer_id" class="form-select" onchange="submitForm()">
                    <option value="">-- ทั้งหมด --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?php echo $c['id'] ?>" <?php if ($c['id'] == $customer_id) echo 'selected'; ?>>
                            <?php echo $c['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <a href="main.php?p=report" class="btn btn-outline-secondary btn-sm">ล้างค่า</a>
                <input type="hidden" name="p" value="report">
            </div>
        </form>

        <div class="table-responsive">
            <table id="tableData" class="table table-borderless table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th>วันที่เบิก</th>
                    <th>สายลูกค้า</th>
                    <th>รหัสลูกค้า</th>
                    <th>ชื่อลูกค้า</th>
                    <th class="text-end">น้ำหนัก (กก.)</th>
                    <th class="text-end">ยอดขาย (บาท)</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sum_kg = 0;
                $sum_amount = 0;

                if(count($sales)>0){
                    foreach ($sales as $s):
                        $sum_kg += $s['total_kg'];
                        $sum_amount += $s['total_amount'];
                        ?>
                        <tr>
                            <td><?php echo $s['withdrawal_date'] ?></td>
                            <td><?php echo $s['route_name'] ?></td>
                            <td><?php echo $s['customer_code'] ?></td>
                            <td><?php echo $s['customer_name'] ?></td>
                            <td class="text-end"><?php echo number_format($s['total_kg'], 2) ?></td>
                            <td class="text-end">฿<?php echo number_format($s['total_amount'], 2) ?></td>
                        </tr>
                    <?php
                    endforeach;
                }else{ ?>
                    <tr><td colspan="6" class="text-center text-danger">ไม่มีข้อมูล</td></tr>
                <?php } ?>
                </tbody>
                <tfoot class="table-light">
                <tr>
                    <th colspan="4" class="text-end">รวม</th>
                    <th class="text-end"><?php echo number_format($sum_kg, 2) ?></th>
                    <th class="text-end">฿<?php echo number_format($sum_amount, 2) ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script src="../assets/libs/flatpickr/flatpickr.js"></script>
<script src="../assets/libs/flatpickr/flatpickr-th.js"></script>
<script src="../assets/libs/select2/js/select2.min.js"></script>
<script>
    function submitForm() {
        //document.getElementById('filterForm').submit();
        $("#filterForm").submit();
    }

    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d",
        locale: 'th'
    });

    $('#route_id, #customer_id').select2({ theme: 'bootstrap-5', width: '100%' });
</script>
</body>
</html>