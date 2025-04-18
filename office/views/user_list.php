<?php
session_start();
$myID = $_SESSION['user_id'];

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
    <link rel="stylesheet" href="../assets/libs/datatablesBootstrap5/css/dataTables.bootstrap5.css">
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
                <li class="breadcrumb-item active" aria-current="page">ผู้ใช้งาน</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">รายชื่อผู้ใช้งาน</h4>
<!--            <button id="btnModal" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">+ เพิ่มผู้ใช้งาน</button>-->
            <a id="btnModal" href="user_create.php" class="btn btn-primary">+ เพิ่มผู้ใช้งาน</a>
        </div>

        <div class="">
            <table id="example" class="table table-borderless table-striped table-hover">
                <thead class="table-dark">
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
                            <!--<a href="#" onclick="setEdit(<?php /*echo $u['id'] */?>);" class="btn btn-sm btn-outline-secondary">แก้ไข</a>-->
                            <!--<a href="#" onclick="setDel(<?php /*echo $u['id'] */?>);" class="btn btn-sm btn-outline-secondary">ลบ</a>-->
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">เพิ่มผู้ใช้งาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อ - นามสกุล</label>
                            <input type="text" id="inputName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ชื่อเล่น</label>
                            <input type="text" id="nickname" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" id="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">อีเมล</label>
                            <input type="email" id="email"  class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" id="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">บทบาท</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">-- เลือกบทบาท --</option>
                                <option value="sales">เซล</option>
                                <option value="owner">เจ้าของ</option>
                                <option value="admin">แอดมิน</option>
                            </select>
                        </div>
                        <div class="col-12" id="route-section" style="display: none;">
                            <label class="form-label">สายลูกค้าที่ดูแล (เฉพาะเซล)</label>
                            <select name="route_ids[]" class="form-select" multiple id="route_ids">
                                <?php foreach ($routes as $r): ?>
                                    <option value="<?php echo $r['id'] ?>"><?php echo $r['route_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" onclick="saveForm();" class="btn btn-success">บันทึกผู้ใช้งาน</button>
                            <input type="hidden" id="formAction" value="add">
                            <input type="hidden" id="userID" value="<?php echo $myID; ?>">
                            <input type="hidden" id="editID" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- modal-body -->
    </div>


</div>

<script src="../assets/libs/datatablesBootstrap5/js/dataTables2.2.2.js"></script>
<script src="../assets/libs/datatablesBootstrap5/js/dataTables.bootstrap5.js"></script>
<script>
    const formAction = $("#formAction");
    const userID = $("#userID");
    const editID = $("#editID");
    const inputName = $("#inputName");

    function saveForm() {
        console.log('saveForm');

        payload = {
            "action": formAction.val(),
            "userID": userID.val(),
            "editID": editID.val(),
            "inputName": inputName.val(),
            "token": Math.random()
        };

        const ajaxSaveForm = $.ajax({
            url: "../models/user_action.php",
            method: 'POST',
            async: false,
            cache: false,
            dataType: 'json',
            data: payload
        });

        ajaxSaveForm.done(function(res) {

            return true;
        });

        ajaxSaveForm.fail(function(xhr, status, error) {
            console.log(" ajax SaveForm call fail!!");
            console.log(status + ": " + error);
            return false;
        });

    }//saveForm


    function setEdit(id) {
        console.log("load function setEdit");
        payload = {
            "action": "getEdit",
            "editID": id,
            "token": Math.random()
        };

        const ajaxSetEdit = $.ajax({
            url: "../models/user_action.php",
            method: 'POST',
            async: false,
            cache: false,
            dataType: 'json',
            data: payload
        });

        ajaxSetEdit.done(function(res) {
            console.log(res);
            //formAction.val('edit');
            editID.val(id);
            inputName.val(res.name);

            return true;
        });

        ajaxSetEdit.fail(function(xhr, status, error) {
            console.log(" ajax ajaxSetEdit call fail!!");
            console.log(status + ": " + error);
            return false;
        });

    }//set edit

    function setDel(id) {
        console.log("delete item")
    }

    $(()=>{
        new DataTable('#example', {
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ]
        });
    });//ready
</script>


</body>
</html>
<?php
function roleName($role)
{
    if ($role == 'sales') { return 'เซล'; }
    else if ($role == 'owner') { return 'เจ้าของ'; }
}
?>