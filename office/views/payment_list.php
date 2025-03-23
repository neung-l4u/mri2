<?php
global $db;
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales') {
    header("Location: ../index.php");
    exit;
}

$salesperson_id = $_SESSION['salesperson_id'];

$routes = $db->query("SELECT r.id, r.route_name FROM customer_routes r
                      JOIN customers c ON r.id = c.route_id
                      WHERE c.salesperson_id = ? AND r.deleted_at IS NULL AND r.status = 'on'
                      GROUP BY r.id, r.route_name
                      ORDER BY r.route_name", $salesperson_id)->fetchAll();

$route_id = $_GET['route_id'] ?? '';
$customer_id = $_GET['customer_id'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

$sql = "SELECT p.id, p.payment_date, p.amount_paid, c.customer_code, c.name AS customer_name, r.route_name
        FROM payments p
        JOIN customers c ON p.customer_id = c.id
        JOIN customer_routes r ON c.route_id = r.id
        WHERE c.salesperson_id = ? AND p.deleted_at IS NULL";
$params = [$salesperson_id];

if ($route_id !== '') {
    $sql .= " AND c.route_id = ?";
    $params[] = $route_id;
}
if ($customer_id !== '') {
    $sql .= " AND c.id = ?";
    $params[] = $customer_id;
}
if ($from_date !== '') {
    $sql .= " AND p.payment_date >= ?";
    $params[] = $from_date;
}
if ($to_date !== '') {
    $sql .= " AND p.payment_date <= ?";
    $params[] = $to_date;
}

$sql .= " ORDER BY p.payment_date DESC, p.id DESC";
$payments = $db->query($sql, ...$params)->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ประวัติการรับชำระเงิน</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/libs/select2/css/select2.min.css" rel="stylesheet">
    <link href="../assets/libs/select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="../assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
    <script src="../assets/libs/jQuery-v3.7.1/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-5">
    <?php include "topNav.php"; ?>
    <div class="mb-3 card rounded border-0 shadow-sm">
        <nav class="card-body" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">แดชบอร์ด</a></li>
                <li class="breadcrumb-item active" aria-current="page">ประวัติการรับชำระเงิน</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h5 class="mb-4">ตัวกรอง</h5>

        <form method="GET" class="row g-3 mb-4" id="filterForm">
            <div class="col-md-3">
                <label for="from_date" class="form-label">ช่วงวันที่ (จาก)</label>
                <input autocomplete="off" placeholder="ปปปป/ดด/วว" type="text" id="from_date" name="from_date" class="form-control flatpickr-date" value="<?php echo htmlspecialchars($from_date); ?>">
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label">ช่วงวันที่ (ถึง)</label>
                <input autocomplete="off" placeholder="ปปปป/ดด/วว" type="text" id="to_date" name="to_date" class="form-control flatpickr-date" value="<?php echo htmlspecialchars($to_date); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">สายลูกค้า</label>
                <select name="route_id" id="route_id" class="form-select">
                    <option value="">-- ทั้งหมด --</option>
                    <?php foreach ($routes as $r): ?>
                        <option value="<?php echo $r['id'] ?>" <?php if ($r['id'] == $route_id) echo 'selected'; ?>>
                            <?php echo $r['route_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">ลูกค้า</label>
                <select name="customer_id" id="customer_id" class="form-select">
                    <option value="">-- ทั้งหมด --</option>
                </select>
            </div>
            <div class="col-12">
                <a href="payment_list.php" class="btn btn-outline-secondary btn-sm">ล้างค่า</a>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">ประวัติการรับชำระเงิน</h4>
            <!--<a href="payment_export_pdf.php?from_date=<?php /*echo $from_date */?>&to_date=<?php /*echo $to_date */?>&route_id=<?php /*echo $route_id */?>&customer_id=<?php /*echo $customer_id */?>"
               class="btn btn-outline-danger btn-sm" target="_blank">
                📄 Export PDF
            </a>-->
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                <tr>
                    <th style="width: 120px">วันที่รับเงิน</th>
                    <th style="width: 100px">รหัสลูกค้า</th>
                    <th>ชื่อลูกค้า</th>
                    <th style="width: 200px">สาย</th>
                    <th style="width: 200px" class="text-end">ยอดที่รับ (บาท)</th>
                    <th style="width: 120px" class="text-center">รายละเอียด</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?php echo $p['payment_date'] ?></td>
                        <td><?php echo $p['customer_code'] ?></td>
                        <td><?php echo $p['customer_name'] ?></td>
                        <td><?php echo $p['route_name'] ?></td>
                        <td class="text-end">฿<?php echo number_format($p['amount_paid'], 2) ?></td>
                        <td class="text-end"><a href="payment_success.php?payment_id=<?php echo $p['id'] ?>" class="btn btn-sm btn-outline-primary">ดู</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="../assets/libs/select2/js/select2.min.js"></script>
<script src="../assets/libs/flatpickr/flatpickr.js"></script>
<script src="../assets/libs/flatpickr/flatpickr-th.js"></script>
<script>
    const customer_id = $('#customer_id');
    const route_id_select = $('#route_id');
    let preventAutoSubmit = true;

    function submitForm() {
        document.getElementById('filterForm').submit();
    }

    $(function () {
        // Select2
        route_id_select.select2({
            theme: 'bootstrap-5',
            placeholder: 'เลือกสายลูกค้า',
            width: '100%'
        });

        customer_id.select2({
            theme: 'bootstrap-5',
            placeholder: 'เลือกลูกค้า',
            width: '100%',
            language: {
                noResults: function () {
                    return 'กรุณาเลือกสายลูกค้าก่อน';
                }
            }
        });

        // Flatpickr
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            locale: 'th',
            allowInput: true,
            onChange: function () {
                submitForm();
            }
        });

        function loadCustomers(routeId, selectedCustomer) {
            customer_id.off('change');
            customer_id.html('<option value="">-- ทั้งหมด --</option>');
            if (!routeId) return;

            $.get('../models/load_customers_by_route.php', { route_id: routeId }, function (data) {
                for (const c of data) {
                    const selected = (selectedCustomer && selectedCustomer === c.id) ? 'selected' : '';
                    customer_id.append(`<option value="${c.id}" ${selected}>${c.name}</option>`);
                }
                preventAutoSubmit = false;
            }, 'json');
        }

        customer_id.on('change', function () {
            if (!preventAutoSubmit) submitForm();
        });

        route_id_select.on('change', function () {
            submitForm();
        });

        loadCustomers('<?php echo $route_id ?>', '<?php echo $customer_id ?>');
    });
</script>
</body>
</html>
