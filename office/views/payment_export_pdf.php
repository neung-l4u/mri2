<?php
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

require_once __DIR__ . '/../vendor/autoload.php';
use Mpdf\Mpdf;

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales') {
    exit('ไม่อนุญาตให้เข้าถึง');
}

$salesperson_id = $_SESSION['salesperson_id'];
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$route_id = $_GET['route_id'] ?? '';
$customer_id = $_GET['customer_id'] ?? '';

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

$mpdf = new Mpdf([ 'default_font' => 'thsarabunnew', 'format' => 'A4' ]);
$mpdf->SetTitle('ประวัติการรับชำระเงิน');

$html = '<h3 style="text-align: center;">ประวัติการรับชำระเงิน</h3>';
$html .= '<table border="1" cellpadding="6" cellspacing="0" width="100%">';
$html .= '<thead>
            <tr style="background-color: #eee;">
              <th width="15%">วันที่</th>
              <th width="15%">รหัสลูกค้า</th>
              <th width="25%">ชื่อลูกค้า</th>
              <th width="25%">สาย</th>
              <th width="20%" align="right">ยอดชำระ (บาท)</th>
            </tr>
          </thead><tbody>';
foreach ($payments as $p) {
    $html .= '<tr>';
    $html .= '<td>' . $p['payment_date'] . '</td>';
    $html .= '<td>' . $p['customer_code'] . '</td>';
    $html .= '<td>' . $p['customer_name'] . '</td>';
    $html .= '<td>' . $p['route_name'] . '</td>';
    $html .= '<td align="right">' . number_format($p['amount_paid'], 2) . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

$mpdf->WriteHTML($html);
$mpdf->Output('payment_list.pdf', 'I');
exit;
