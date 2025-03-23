<?php
global $db;
session_start();
include_once('../assets/db/db.php');
include_once('../assets/db/initDB.php');

// ดึงลูกค้าทั้งหมดที่เซลดูแล (เซล ID = 1 ชั่วคราว)
$salesperson_id = 1;
$customers = $db->query("SELECT id, customer_code, name FROM customers WHERE salesperson_id = ? AND deleted_at IS NULL AND status = 'on'", $salesperson_id)->fetchAll();

// ดึงสินค้าและราคาขายเฉพาะลูกค้า (ใช้ลูกค้าแรกเป็นตัวอย่าง)
$customer_id = $_GET['customer_id'] ?? ($customers[0]['id'] ?? 0);

$products = $db->query("SELECT p.id, p.name, p.package_size_grams, cpp.price_per_pack
                          FROM products p
                          JOIN customer_product_prices cpp ON cpp.product_id = p.id AND cpp.customer_id = ?
                          WHERE p.deleted_at IS NULL AND p.status = 'on'
                          ORDER BY p.name"
    , $customer_id
)->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เบิกสินค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container p-5">
<div>
    <h3 class="mb-4">เบิกสินค้าให้ลูกค้า</h3>
    <form method="POST" action="withdraw_store.php">
        <div class="mb-3">
            <label for="customer_id" class="form-label">เลือกลูกค้า</label>
            <select class="form-select" id="customer_id" name="customer_id" onchange="location = '?customer_id=' + this.value">
                <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo $customer['id'] ?>" <?php echo $customer['id'] == $customer_id ? 'selected' : '' ?>>
                        <?php echo $customer['customer_code'] ?> - <?php echo $customer['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="withdrawal_date" class="form-label">วันที่เบิก</label>
            <input id="withdrawal_date" type="date" class="form-control" name="withdrawal_date" value="<?php echo date('Y-m-d') ?>">
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
                        <?php echo htmlspecialchars($product['name']) ?> (<?php echo $product['package_size_grams'] ?>g)
                        <input type="hidden" name="product_id[]" value="<?php echo $product['id'] ?>">
                    </td>
                    <td><input aria-label="quantity" type="number" name="qty[]" class="form-control qty" step="0.1" min="0" value="0"></td>
                    <td><input aria-label="price" type="number" name="price[]" class="form-control price" value="<?php echo $product['price_per_pack'] ?>" readonly></td>
                    <td><input aria-label="total" type="number" class="form-control total" value="0.00" readonly></td>
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
    $(()=>{
        $('input.qty').on('input', function () {
            const row = $(this).closest('tr');
            const qty = parseFloat($(this).val()) || 0;
            const price = parseFloat(row.find('.price').val()) || 0;
            const total = qty * price;
            row.find('.total').val(total.toFixed(2));

            let grandTotal = 0;
            $('.total').each(function () {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('#grandTotal').text(grandTotal.toFixed(2));
        });
    });//ready
</script>
</body>
</html>