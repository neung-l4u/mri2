<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

// ดึงวันที่ปัจจุบัน และแปลงเป็น พ.ศ.
$today = date("Y-m-d"); // ได้เป็น YYYY-MM-DD (ค.ศ.)
$thYear = date("Y") ; // แปลง ค.ศ. เป็น พ.ศ.
$thDate = str_replace(date("Y"), $thYear, $today); // เปลี่ยนเฉพาะปีเป็น พ.ศ.
$thDateWithoutDash = str_replace("-", "/", $thDate); // ลบเครื่องหมาย -
$thYearLast2Digits = substr($thYear, -2); // ลดปีให้เหลือแค่ 2 หลัก

$codenum = "QUOMRI-" . $thYearLast2Digits . date("md");

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
    <title>ใบเสนอราคา</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        ::placeholder {
            color: #aaaaaa !important;
            opacity: 1; /* Firefox */
            font-size: 0.8rem;
        }

        ::-ms-input-placeholder { /* Edge 12 -18 */
            color: #aaaaaa !important;
        }

        legend{
            font-size: 1.2rem;
            font-weight: bold;
        }
        fieldset{
            /*border: 1px solid #cccccc;*/
            margin-bottom: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard_owner.php">แดชบอร์ด</a></li>
                <li class="breadcrumb-item active" aria-current="page">ใบเสนอราคา</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">ใบเสนอราคา</h4>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-end">
                            </div>
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-body table-responsive p-4">
                                        <form id="formquo" method="GET" action="../assets/docs/quotation_2025_V01.php" target="_blank">

                                            <div class="d-flex flex-row justify-content-between">
                                                <div class="d-flex flex-column align-content-start"  style="width: 30%"> <!-- left side -->
                                                    <h5>รายละเอียดเอกสาร</h5>
                                                    <div class="form-group d-flex flex-row justify-content-between align-items-baseline px-3">
                                                        <label style="min-width: 70px;" for="datepicker">วันที่</label>
                                                        <input type="date" class="form-control" id="datepicker" name="datepicker" onchange="updateDate();" value="<?php echo $thDate; ?>">
                                                    </div>

                                                    <div class="form-group d-flex flex-row justify-content-between align-items-baseline px-3">
                                                        <label style="min-width: 90px;">รหัสเอกสาร</label>

                                                        <input type="text" class="form-control" id="codenum" name="codenum" class="field left" value=" <?php echo $codenum; ?>" readonly>
                                                    </div>
                                                </div> <!-- end left side -->

                                                <div class="d-flex flex-column align-content-start pl-4"  style="width: 70%"> <!-- right side -->
                                                    <h5>รายละเอียดผู้ติดต่อ</h5>
                                                    <div class="form-group d-flex flex-row justify-content-between align-items-baseline px-3">
                                                        <label for="namequo" style="width: 100px;">เสนอคุณ</label>
                                                        <input type="text" class="form-control" name="namequo" id="namequo" placeholder="คุณ ณัฐิยา จ้อยเอม (คุณหยก)">
                                                    </div>
                                                    <div class="form-group d-flex flex-row justify-content-between align-items-baseline px-3">
                                                        <label for="companyquo" style="width: 100px;">บริษัท</label>
                                                        <input type="text" class="form-control" name="companyquo" id="companyquo" placeholder="บริษัท โลคอลอีส จำกัด (ประเทศไทย)">
                                                    </div>

                                                    <div class="form-group d-flex flex-row justify-content-between align-items-baseline px-3">
                                                        <label for="phonequo" style="width: 100px;">เบอร์โทร</label>
                                                        <input type="tel" class="form-control" name="phonequo" id="phonequo" placeholder="0812345678">
                                                    </div>
                                                    <div class="form-group d-flex flex-row justify-content-between align-items-baseline px-3">
                                                        <label for="emailquo" style="width: 100px;">อีเมล</label>
                                                        <input type="text" class="form-control" name="emailquo" id="emailquo" placeholder="someone@example.com">
                                                    </div>

                                                </div> <!-- end right side -->
                                            </div>

                                            <fieldset class="border rounded w-100 p-3">
                                                <legend>รายละเอียดสินค้าช่องที่ 1:</legend>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <label for="rowOneMenu1">รายการสินค้า</label>
                                                        <input type="text" class="form-control" id="rowOneMenu1" name="rowOneMenu1" placeholder="ตัวอย่าง: ลูกชิ้นเนื้อวัวออสเตรเลีย เนื้อล้วน 100 กรัม">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <label for="rowOnePrice1">ราคาต่อหน่วย</label>
                                                        <input type="text" class="form-control" id="rowOnePrice1" name="rowOnePrice1" placeholder="ตัวอย่าง: ราคา 150 บาท ต่อ กิโลกรัม">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <input type="text" class="form-control" id="rowOneMenu2" name="rowOneMenu2" placeholder="ตัวอย่าง: ลูกชิ้นเนื้อวัวออสเตรเลีย เอ็นเนื้อ 100 กรัม">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <input type="text" class="form-control" id="rowOnePrice2" name="rowOnePrice2" placeholder="ตัวอย่าง: ราคา 150 บาท ต่อ กิโลกรัม">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <input type="text" class="form-control" id="rowOneMenu3" name="rowOneMenu3">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <input type="text" class="form-control" id="rowOnePrice3" name="rowOnePrice3">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <input type="text" class="form-control" id="rowOneMenu4" name="rowOneMenu4">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <input type="text" class="form-control" id="rowOnePrice4" name="rowOnePrice4">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <div class="form-group px-3" style="width: 100%">
                                                        <label for="rowOneNote">หมายเหตุ</label>
                                                        <textarea class="form-control" id="rowOneNote" rows="2" name="rowOneNote" placeholder="ราคานี้สั่งสินค้าขั้นตํ่า 250 กก. ต่อ 1 รอบส่ง ต่อรถ 1 คัน"></textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- /. row1 -->





                                            <fieldset class="border rounded w-100 p-3">
                                                <legend>รายละเอียดสินค้าช่องที่ 2:</legend>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <label for="rowTwoMenu1">รายการสินค้า</label>
                                                        <input type="text" class="form-control" id="rowTwoMenu1" name="rowTwoMenu1" placeholder="ตัวอย่าง: ลูกชิ้นเนื้อวัวออสเตรเลีย เนื้อล้วน 100 กรัม">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <label for="rowTwoPrice1">ราคาต่อหน่วย</label>
                                                        <input type="text" class="form-control" id="rowTwoPrice1" name="rowTwoPrice1" placeholder="ตัวอย่าง: ราคา 150 บาท ต่อ กิโลกรัม">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <input type="text" class="form-control" id="rowTwoMenu2" name="rowTwoMenu2" placeholder="ตัวอย่าง: ลูกชิ้นเนื้อวัวออสเตรเลีย เอ็นเนื้อ 100 กรัม">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <input type="text" class="form-control" id="rowTwoPrice2" name="rowTwoPrice2" placeholder="ตัวอย่าง: ราคา 150 บาท ต่อ กิโลกรัม">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <input type="text" class="form-control" id="rowTwoMenu3" name="rowTwoMenu3">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <input type="text" class="form-control" id="rowTwoPrice3" name="rowTwoPrice3">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <input type="text" class="form-control" id="rowTwoMenu4" name="rowTwoMenu4">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <input type="text" class="form-control" id="rowTwoPrice4" name="rowTwoPrice4">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <div class="form-group px-3" style="width: 100%">
                                                        <label for="rowTwoNote">หมายเหตุ</label>
                                                        <textarea class="form-control" id="rowTwoNote" rows="2" name="rowTwoNote" placeholder="ราคานี้สั่งสินค้าขั้นตํ่า 250 กก. ต่อ 1 รอบส่ง ต่อรถ 1 คัน"></textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- /. row2 -->




                                            <fieldset class="border rounded w-100 p-3">
                                                <legend>รายละเอียดสินค้าช่องที่ 3:</legend>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <label for="rowThrMenu1">รายการสินค้า</label>
                                                        <input type="text" class="form-control" id="rowThrMenu1" name="rowThrMenu1" placeholder="ตัวอย่าง: ลูกชิ้นเนื้อวัวออสเตรเลีย เนื้อล้วน 100 กรัม">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <label for="rowThrPrice1">ราคาต่อหน่วย</label>
                                                        <input type="text" class="form-control" id="rowThrPrice1" name="rowThrPrice1" placeholder="ตัวอย่าง: ราคา 150 บาท ต่อ กิโลกรัม">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <input type="text" class="form-control" id="rowThrMenu2" name="rowThrMenu2" placeholder="ตัวอย่าง: ลูกชิ้นเนื้อวัวออสเตรเลีย เอ็นเนื้อ 100 กรัม">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <input type="text" class="form-control" id="rowThrPrice2" name="rowThrPrice2" placeholder="ตัวอย่าง: ราคา 150 บาท ต่อ กิโลกรัม">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <input type="text" class="form-control" id="rowThrMenu3" name="rowThrMenu3">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <input type="text" class="form-control" id="rowThrPrice3" name="rowThrPrice3">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="px-3" style="width: 70%">
                                                        <input type="text" class="form-control" id="rowThrMenu4" name="rowThrMenu4">
                                                    </div>
                                                    <div class="px-3" style="width: 30%">
                                                        <input type="text" class="form-control" id="rowThrPrice4" name="rowThrPrice4">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <div class="form-group px-3" style="width: 100%">
                                                        <label for="rowThrNote">หมายเหตุ</label>
                                                        <textarea class="form-control" id="rowThrNote" rows="2" name="rowThrNote" placeholder="ราคานี้สั่งสินค้าขั้นตํ่า 250 กก. ต่อ 1 รอบส่ง ต่อรถ 1 คัน"></textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- /. row3 -->

                                            <div class="d-flex flex-row-reverse">
                                                <button
                                                        type="button"
                                                        class="btn btn-primary"
                                                        id="submitquo"
                                                        onclick="submitForm()">สร้างเอกสาร
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                    </div><!-- /.col-md-12 -->
                </div><!-- /.row -->

            </div><!-- /.container-fluid -->
        </div>
    </div>
</div>
</body>
</html>
    <script>

        $(document).ready(function () {
            updateDate(); // เรียกใช้เมื่อโหลดหน้าเว็บ
            $("#datepicker").on("change", updateDate); // อัปเดตเมื่อเลือกวันที่
        });

        function submitForm() {
            // let formData = $("#formquo").serialize();
            // console.log(formData);
            document.getElementById("formquo").submit();
        }

        function updateDate() {
            let dateValue = $("#datepicker").val();

            // ถ้ายังไม่มีค่า ให้ใช้วันที่ปัจจุบัน
            if (!dateValue) {
                let today = new Date();
                let year = today.getFullYear();
                let month = String(today.getMonth() + 1).padStart(2, "0");
                let day = String(today.getDate()).padStart(2, "0");
                dateValue = `${year}-${month}-${day}`;
                $("#datepicker").val(dateValue); // เซ็ตค่าให้ datepicker ด้วย
            }

            let dateObj = new Date(dateValue);
            let day = String(dateObj.getDate()).padStart(2, "0");
            let month = String(dateObj.getMonth() + 1).padStart(2, "0");
            let year = dateObj.getFullYear();
            let last2DigitsYear = String(year).slice(-2);

            let textPrefix = "QUOMRI-";
            let formattedDate = textPrefix + last2DigitsYear + month + day;

            $("#codenum").val(formattedDate); // อัปเดตช่อง codenum
        }



    </script>

<?php
function roleName($role)
{
    if ($role == 'sales') { return 'เซล'; }
    else if ($role == 'owner') { return 'เจ้าของ'; }
}
?>