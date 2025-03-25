<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$users = $db->query("SELECT * FROM users WHERE deleted_at IS NULL ORDER BY role desc, name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการผู้ใช้งาน</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard_owner.php">แดชบอร์ด</a></li>
                <li class="breadcrumb-item active" aria-current="page">ผู้ใช้งาน</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">รายชื่อผู้ใช้งาน</h4>
            <a href="user_create.php" class="btn btn-primary btn-sm">+ เพิ่มผู้ใช้งาน</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                <tr>
                    <th>ชื่อ</th>
                    <th>ชื่อเล่น</th>
                    <th>เบอร์โทร</th>
                    <th>อีเมล</th>
                    <th>บทบาท</th>
                    <th>สายลูกค้า</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <?php
                    $routes = [];
                    if ($u['role'] === 'sales' && $u['salesperson_id']) {
                        $routes = $db->query("SELECT DISTINCT r.route_name FROM customers c JOIN customer_routes r ON c.route_id = r.id WHERE c.salesperson_id = ? AND r.deleted_at IS NULL", $u['salesperson_id'])->fetchAll();
                    }
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['name']) ?></td>
                        <td><?php echo htmlspecialchars($u['nickname']) ?></td>
                        <td><?php echo htmlspecialchars($u['phone']) ?></td>
                        <td><?php echo htmlspecialchars($u['email']) ?></td>
                        <td><?php echo htmlspecialchars(roleName($u['role'])) ?></td>
                        <td>
                            <?php
                            if ($u['role'] === 'sales') {
                                echo !empty($routes)
                                    ? implode(', ', array_column($routes, 'route_name'))
                                    : '<span class="text-muted">-</span>';
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td><?php echo $u['status'] === 'on' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?></td>
                        <td>
                            <a href="user_edit.php?id=<?php echo $u['id'] ?>" class="btn btn-sm btn-outline-secondary">แก้ไข</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
<?php
function roleName($role)
{
    if ($role == 'sales') { return 'เซล'; }
    else if ($role == 'owner') { return 'เจ้าของ'; }
}
?>