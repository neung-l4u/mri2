<?php
session_start();
include_once('../assets/db/db.php');
include_once('../assets/db/initDB.php');
global $db;

// ดึง ID เซลจาก session
$salesperson_id = $_SESSION['salesperson_id'] ?? 0;

// ดึงลูกค้าเฉพาะที่เซลดูแล แยกตามสาย
$routes = $db->query("SELECT cr.id, cr.route_name, cr.route_level FROM customer_routes cr WHERE cr.deleted_at IS NULL AND cr.status = 'on'")->fetchAll();
$customers = $db->query("SELECT c.id, c.customer_code, c.name, c.route_id, cr.route_level FROM customers c
    JOIN customer_routes cr ON c.route_id = cr.id
    WHERE c.status = 'on' AND c.deleted_at IS NULL AND c.salesperson_id = ?", $salesperson_id)->fetchAll();

$customer_id = $_GET['customer_id'] ?? ($customers[0]['id'] ?? 0);

// หาระดับตลาดของลูกค้าที่เลือก
$customer_level_row = $db->query("SELECT cr.route_level FROM customers c JOIN customer_routes cr ON c.route_id = cr.id WHERE c.id = ?", $customer_id)->fetchArray();
$route_level = $customer_level_row['route_level'] ?? 'down';

// ดึงสินค้าเฉพาะที่ตรงกับระดับตลาด
$products = $db->query("SELECT p.id, p.name, p.package_size_grams, cpp.price_per_pack, p.default_price_per_pack
  FROM products p
  LEFT JOIN customer_product_prices cpp ON cpp.product_id = p.id AND cpp.customer_id = ?
  WHERE p.deleted_at IS NULL AND p.status = 'on' AND p.productLevel = ?
  ORDER BY p.name", [$customer_id, $route_level])->fetchAll();
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เบิกสินค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <?php include "topNav.php"; ?>
    <h4 class="mb-4">เบิกสินค้าให้ลูกค้า</h4>
    <form method="POST" action="withdraw_store.php">
        <div class="mb-3">
            <label for="customer_id" class="form-label">เลือกลูกค้า</label>
            <select class="form-select" id="customer_id" name="customer_id" onchange="location = 'main.php?p=withdraw&customer_id=' + this.value">
                <?php foreach ($routes as $route): ?>
                    <?php
                    // ดึงลูกค้าในสายนี้ที่เซลดูแล
                    $customers_in_route = array_filter($customers, function ($c) use ($route) {
                        return $c['route_id'] == $route['id'];
                    });
                    ?>
                    <?php if (count($customers_in_route) > 0): ?>
                        <optgroup label="<?php echo $route['route_name']; ?>">
                            <?php foreach ($customers_in_route as $customer): ?>
                                <option value="<?php echo $customer['id']; ?>" <?php echo $customer['id'] == $customer_id ? 'selected' : ''; ?>>
                                    <?php echo $customer['customer_code'] . ': ' . $customer['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="withdrawal_date" class="form-label">วันที่เบิก</label>
            <input id="withdrawal_date" type="date" class="form-control" name="withdrawal_date" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>สินค้า</th>
                <th>จำนวน (กก.)</th>
                <th>ราคาต่อกก.</th>
                <th>รวม</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($product['name']); ?> (<?php echo $product['package_size_grams']; ?>g)
                        <input type="hidden" name="product_id[]" value="<?php echo $product['id']; ?>">
                    </td>
                    <td><input type="number" name="qty[]" class="form-control qty" step="0.1" min="0" value="0" onfocus="this.select()" oninput="this.value = this.value.replace(/[^\d.]/g, '')"></td>
                    <td><input type="number" name="price[]" class="form-control price" value="<?php echo ($product['price_per_pack'] ?? $product['default_price_per_pack']*2); ?>" readonly></td>
                    <td><input type="text" class="form-control total" value="0.00" readonly></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mb-3 text-end">
            <label class="form-label">รวมทั้งหมด</label>
            <h4>฿ <span id="grandTotal">0.00</span></h4>
        </div>

        <button type="submit" class="btn btn-primary">บันทึกการเบิก</button>
    </form>
</div>
<script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/libs/jQuery-v3.7.1/jquery-3.7.1.min.js"></script>
<script>
    $(() => {
        $('input.qty').on('input', function () {
            const row = $(this).closest('tr');
            const qty = parseFloat($(this).val()) || 0;
            const price = parseFloat(row.find('.price').val()) || 0;
            const total = qty * price;
            row.find('.total').val(total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));

            let grandTotal = 0;
            $('.total').each(function () {
                grandTotal += parseFloat($(this).val().replace(/,/g, '')) || 0;
            });
            $('#grandTotal').text(grandTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        });
    });
</script>
</body>
</html>
