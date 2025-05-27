<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$customer_id = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : 0;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$route_id = isset($_GET['route_id']) ? (int)$_GET['route_id'] : 0;

$customer = $db->query("SELECT * FROM customers WHERE id = ? AND deleted_at IS NULL", $customer_id)->fetchArray();
if (!$customer) {
    echo "<div class='alert alert-danger'>ไม่พบข้อมูลลูกค้า</div>";
    exit;
}

$products = $db->query("SELECT p.`id`, p.`productLevel`, pc.name AS 'cat', p.`name`, p.`package_size_grams`, p.`default_price_per_pack`, p.`unitCalculate`, p.`active`, p.`status` FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.id WHERE p.deleted_at IS NULL AND p.status = 'on' ORDER BY `p`.`name` ASC;")->fetchAll();
$customPrices = $db->query("SELECT product_id, custom_price FROM customer_product_prices WHERE customer_id = ? AND deleted_at IS NULL", $customer_id)->fetchAll('product_id');
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ราคาสินค้าเฉพาะลูกค้า: <?php echo htmlspecialchars($customer['name']); ?></title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/libs/jQuery-v3.7.1/jquery-3.7.1.min.js"></script>
    <style>
        input[type=number] {
            text-align:right;
        }
    </style>
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
                <li class="breadcrumb-item"><a href="customer_list.php?route_id=<?php echo $customer['route_id'] ?>">รายการลูกค้า</a></li>
                <li class="breadcrumb-item active" aria-current="page">สินค้า <?php echo htmlspecialchars($customer['name']); ?></li>
            </ol>
        </nav>
    </div>
    <div class="bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4">
            ราคาสินค้าเฉพาะลูกค้า: <?php echo htmlspecialchars($customer['name']); ?>
        </h3>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ บันทึกราคาพิเศษสำเร็จแล้ว</div>
        <?php endif; ?>
        <form method="post" action="customer_products_store.php">
            <input type="hidden" name="id" value="<?php echo $customer_id; ?>">
            <input type="hidden" name="route_id" value="<?php echo $customer_id; ?>">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
            <?php foreach ($customPrices as $cp){
                $newPrice[$cp['product_id']] = $cp['custom_price'];
            } ?>
            <div class="text-end">
                <small class="text-danger">ทุกรายการที่ระบุหมายถึงสินค้าที่สั่งประจำ (ใส่เป็นราคาปรกติได้)</small>
            </div>

            <table id="tableData" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="text-center">#</th>
                        <th>ชื่อสินค้า</th>
                        <th style="width: 150px;" class="text-center">ราคาปกติ</th>
                        <th style="width: 80px;" class="text-center">หน่วย</th>
                        <th style="width: 150px;" class="text-center">ราคาพิเศษ</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i=1;
                foreach ($products as $product):
                    $product_id = $product['id'];
                    $default_price = number_format((float)$product['default_price_per_pack'], 2);
                    $custom_price = isset($newPrice[$product_id]) ? $newPrice[$product_id] : '';
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i; ?></td>
                        <td class="text-start"><?php echo '<small class="text-muted">'.htmlspecialchars($product['cat']).'</small>: '.htmlspecialchars($product['name']).' ('.number_format($product['package_size_grams']).'g.)'; ?></td>
                        <td class="text-end"><?php echo $default_price; ?></td>
                        <td class="text-center"><?php echo $product['unitCalculate'] === 'piece' ? 'ชิ้น' : 'กก.'; ?></td>
                        <td<?php if ($custom_price !== '') echo ' class="bg-warning-subtle"'; ?>>
                            <input type="number" step="1" min="0" class="form-control"
                                   name="custom_prices[<?php echo $product_id; ?>]"
                                   value="<?php echo htmlspecialchars($custom_price); ?>">
                        </td>
                    </tr>
                <?php
                $i++;
                endforeach;
                ?>
                </tbody>
            </table>
            <div class="mt-4 text-end">
                <a href="customer_list.php" class="btn btn-secondary">ย้อนกลับ</a>
                <button type="submit" class="btn btn-primary">บันทึกราคาพิเศษ</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
