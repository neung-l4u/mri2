<?php
global $db;
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales') {
    exit;
}

$customer_id = $_GET['customer_id'] ?? 0;
if (!$customer_id) exit;

$invoices = $db->query("SELECT id, withdrawal_date, total_amount FROM sales_orders WHERE customer_id = ? AND is_paid = 0 AND deleted_at IS NULL ORDER BY withdrawal_date", $customer_id)->fetchAll();

if (count($invoices) === 0) {
    echo '<div class="alert alert-info mt-3">ลูกค้านี้ไม่มีบิลที่ค้างชำระ</div>';
    exit;
}
?>
<div class="mt-4">
    <h6>เลือกบิลที่ต้องการรับชำระ:</h6>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th style="width: 30px; text-align: center;">#</th>
                <th style="width: 300px">วันที่เบิก</th>
                <th style="text-align: center;">ยอดบิล (บาท)</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($invoices as $inv): ?>
                <tr>
                    <td style="text-align: center;">
                        <input aria-label="select[]" type="checkbox" name="sales_order_ids[]" value="<?php echo $inv['id'] ?>" data-amount="<?php echo $inv['total_amount'] ?>" class="bill-check">
                    </td>
                    <td><?php echo $inv['withdrawal_date'] ?></td>
                    <td class="text-end"><?php echo number_format($inv['total_amount'], 2) ?> บาท</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr class="table-light">
                <td colspan="2" class="text-end"><strong>รวมที่เลือก:</strong></td>
                <td class="text-end"><strong id="selected-total">0.00</strong> บาท</td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    $(function () {
        $(document).on('change', '.bill-check', function () {
            let total = 0;
            $('.bill-check:checked').each(function () {
                total += parseFloat($(this).data('amount')) || 0;
            });
            $('#selected-total').text(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        });
    });
</script>
