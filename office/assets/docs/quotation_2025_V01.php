<?php

date_default_timezone_set("Asia/Bangkok");

$data["datepicker"] = !empty($_REQUEST["datepicker"]) ? trim($_REQUEST["datepicker"]) : "";

$redate = explode("-", $data["datepicker"]);
$data["datepicker"] = $redate[2]."/".$redate[1]."/".$redate[0];

$data["codenum"] = !empty($_REQUEST["codenum"]) ? trim($_REQUEST["codenum"]) : "";
$data["namequo"] = !empty($_REQUEST["namequo"]) ? trim($_REQUEST["namequo"]) : "";
$data["companyquo"] = !empty($_REQUEST["companyquo"]) ? trim($_REQUEST["companyquo"]) : "";
$data["phonequo"] = !empty($_REQUEST["phonequo"]) ? trim($_REQUEST["phonequo"]) : "";
$data["emailquo"] = !empty($_REQUEST["emailquo"]) ? trim($_REQUEST["emailquo"]) : "";

$data["rowOneMenu1"] = !empty($_REQUEST["rowOneMenu1"]) ? trim($_REQUEST["rowOneMenu1"]) : "";
$data["rowOnePrice1"] = !empty($_REQUEST["rowOnePrice1"]) ? trim($_REQUEST["rowOnePrice1"]) : "";
$data["rowOneMenu2"] = !empty($_REQUEST["rowOneMenu2"]) ? trim($_REQUEST["rowOneMenu2"]) : "";
$data["rowOnePrice2"] = !empty($_REQUEST["rowOnePrice2"]) ? trim($_REQUEST["rowOnePrice2"]) : "";
$data["rowOneMenu3"] = !empty($_REQUEST["rowOneMenu3"]) ? trim($_REQUEST["rowOneMenu3"]) : "";
$data["rowOnePrice3"] = !empty($_REQUEST["rowOnePrice3"]) ? trim($_REQUEST["rowOnePrice3"]) : "";
$data["rowOneMenu4"] = !empty($_REQUEST["rowOneMenu4"]) ? trim($_REQUEST["rowOneMenu4"]) : "";
$data["rowOnePrice4"] = !empty($_REQUEST["rowOnePrice4"]) ? trim($_REQUEST["rowOnePrice4"]) : "";
$data["rowOneNote"] = !empty($_REQUEST["rowOneNote"]) ? trim($_REQUEST["rowOneNote"]) : "";

$data["rowTwoMenu1"] = !empty($_REQUEST["rowTwoMenu1"]) ? trim($_REQUEST["rowTwoMenu1"]) : "";
$data["rowTwoPrice1"] = !empty($_REQUEST["rowTwoPrice1"]) ? trim($_REQUEST["rowTwoPrice1"]) : "";
$data["rowTwoMenu2"] = !empty($_REQUEST["rowTwoMenu2"]) ? trim($_REQUEST["rowTwoMenu2"]) : "";
$data["rowTwoPrice2"] = !empty($_REQUEST["rowTwoPrice2"]) ? trim($_REQUEST["rowTwoPrice2"]) : "";
$data["rowTwoMenu3"] = !empty($_REQUEST["rowTwoMenu3"]) ? trim($_REQUEST["rowTwoMenu3"]) : "";
$data["rowTwoPrice3"] = !empty($_REQUEST["rowTwoPrice3"]) ? trim($_REQUEST["rowTwoPrice3"]) : "";
$data["rowTwoMenu4"] = !empty($_REQUEST["rowTwoMenu4"]) ? trim($_REQUEST["rowTwoMenu4"]) : "";
$data["rowTwoPrice4"] = !empty($_REQUEST["rowTwoPrice4"]) ? trim($_REQUEST["rowTwoPrice4"]) : "";
$data["rowTwoNote"] = !empty($_REQUEST["rowTwoNote"]) ? trim($_REQUEST["rowTwoNote"]) : "";

$data["rowThrMenu1"] = !empty($_REQUEST["rowThrMenu1"]) ? trim($_REQUEST["rowThrMenu1"]) : "";
$data["rowThrPrice1"] = !empty($_REQUEST["rowThrPrice1"]) ? trim($_REQUEST["rowThrPrice1"]) : "";
$data["rowThrMenu2"] = !empty($_REQUEST["rowThrMenu2"]) ? trim($_REQUEST["rowThrMenu2"]) : "";
$data["rowThrPrice2"] = !empty($_REQUEST["rowThrPrice2"]) ? trim($_REQUEST["rowThrPrice2"]) : "";
$data["rowThrMenu3"] = !empty($_REQUEST["rowThrMenu3"]) ? trim($_REQUEST["rowThrMenu3"]) : "";
$data["rowThrPrice3"] = !empty($_REQUEST["rowThrPrice3"]) ? trim($_REQUEST["rowThrPrice3"]) : "";
$data["rowThrMenu4"] = !empty($_REQUEST["rowThrMenu4"]) ? trim($_REQUEST["rowThrMenu4"]) : "";
$data["rowThrPrice4"] = !empty($_REQUEST["rowThrPrice4"]) ? trim($_REQUEST["rowThrPrice4"]) : "";
$data["rowThrNote"] = !empty($_REQUEST["rowThrNote"]) ? trim($_REQUEST["rowThrNote"]) : "";



?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quotation</title>
    <link href="../libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../css/quotation_form.css" rel="stylesheet" type="text/css">
    <style>
        body {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
        .address{
            font-size: 0.7rem;
            line-height: 1.5rem;
        }
        .memo{
            font-size: 0.7rem;
        }
    </style>
</head>
<body>
<div class="container-fluid">

    <div class="row">

        <div class="d-flex flex-row-reverse no-print">
            <div class="col-2">
                <button type="button" class="btn btn-primary" id="btn_print" onclick="window.print();">ปริ้นเอกสาร</button>
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="../../views/main.php?p=dashboard">
                                <svg class="nav-icon mr-2" height="1.2em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                                แดชบอร์ด
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="../../views/main.php?p=quotation">ใบเสนอราคา</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
    <div class="container py-5 px-5">
        <div class="d-flex flex-column">
        <div class="row">
            <div class="col">
                <div class="d-flex flex-row">
                    <div class="col-4">
                        <img src="../img/halal.png" width="60px" alt="halal">
                    </div>
                    <div class="col-4 text-center">
                        <img src="../img/logo.jpg" width="150px" alt="logo">
                    </div>
                    <div class="col-4">
                    </div>
                </div>
                <!-- logo -->

                <div class="d-flex flex-row mb-4">
                    <div class="col">
                        <div class="address"><b>บริษัท เอ็ม อาร์ ฮาลาล อินเตอร์ฟู้ด จำกัด (สำนักงานใหญ่)</b></div>
                        <div class="address"><b>เลขที่ :</b> 110/37 หมู่ที่ 9 ตำบลบางปลา อำเภอบางพลี จังหวัดสมุทรปราการ 10540</div>
                        <div class="address"><b>โทรศัพท์ :</b> 08-6829-1935   <b>เลขที่ประจำตัวผู้เสียภาษี :</b> 0-11555802076</div>
                    </div>
                </div>
                <!-- detail company -->


                <div class="d-flex flex-row">
                    <div class="col text-center">
                        <h5>ใบเสนอราคา/Quotation</h5>
                    </div>
                </div>
                <!-- title -->

                <div class="d-flex flex-row mb-4">
                    <div class="col">
                        <table>
                            <thead>
                            <tr>
                                <td class="namecus" rowspan="3" style="width: 50%"><b>Attention</b> : คุณ <?php echo $data["namequo"];?><br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data["companyquo"];?><br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tel.<?php echo $data["phonequo"];?></td>
                                <td class="nocus" rowspan="2" style="width: 50%">NO : <?php echo $data["codenum"];?></td>
                            </tr>
                            <tr>
                            </tr>
                            <tr>
                                <td class="nocus" style="width: 50%">Date : <?php echo $data["datepicker"];?></td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- name/company/tel/ -->

                <small class="memo">
                    We are please to submit you the folowing described here in at price. items and terms stated:
                </small>
                <div class="d-flex flex-row mb-1">
                    <table>

                        <tr>
                            <thead>
                                <tr>
                                    <th class="tcol1" style="text-align: center; background-color: #F3EEC2;">ลำดับ</th>
                                    <th class="tcol2" style="text-align: center; background-color: #F3EEC2;">รายการสินค้า</th>
                                    <th class="tcol3" style="text-align: center; background-color: #F3EEC2;">ราคา</th>
                                    <th class="tcol4" style="text-align: center; background-color: #F3EEC2;">หมายเหตุ</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                            if($data["rowOneMenu1"] > NULL){
                            ?>
                                <tr>
                                    <td class="tcol1">1</td>
                                    <td class="tcol2"><?php echo $data["rowOneMenu1"];?></td>
                                    <td class="tcol3"><?php echo $data["rowOnePrice1"];?></td>
                                    <td class="tcol4" rowspan="4" style="text-align: center;"><?php echo $data["rowOneNote"];?></td>
                                </tr>
                            <?php };
                            ?>

                            <?php
                            if($data["rowOneMenu2"] > NULL){
                                ?>
                                <tr>
                                    <td class="tcol1">2</td>
                                    <td class="tcol2"><span><?php echo $data["rowOneMenu2"];?></span></td>
                                    <td class="tcol3"><span><?php echo $data["rowOnePrice2"];?></span></td>
                                </tr>
                            <?php };
                            ?>

                            <?php
                            if($data["rowOneMenu3"] > NULL){
                            ?>
                                <tr>
                                    <td class="tcol1">3</td>
                                    <td class="tcol2"><span><?php echo $data["rowOneMenu3"];?></span></td>
                                    <td class="tcol3"><span><?php echo $data["rowOnePrice3"];?></span></td>
                                </tr>
                            <?php };
                            ?>

                            <?php
                            if($data["rowOneMenu4"] > NULL){
                            ?>
                                <tr>
                                    <td class="tcol1">4</td>
                                    <td class="tcol2"><span><?php echo $data["rowOneMenu4"];?></span></td>
                                    <td class="tcol3"><span><?php echo $data["rowOnePrice4"];?></span></td>
                                </tr>
                            <?php };
                            ?>

                            </tbody>
                        </tr>


                        <?php
                        if($data["rowTwoMenu1"] > NULL){
                        ?>
                        <tr>
                            <tbody style="border-top: 2px solid #000000;">
                        <tr>
                            <td class="tcol1">1</td>
                            <td class="tcol2"><?php echo $data["rowTwoMenu1"];?></td>
                            <td class="tcol3"><?php echo $data["rowTwoPrice1"];?></td>
                            <td class="tcol4" rowspan="4" style="text-align: center;"><?php echo $data["rowTwoNote"];?></td>
                        </tr>
                        <?php };
                        ?>

                        <?php
                        if($data["rowTwoMenu2"] > NULL){
                        ?>
                        <tr>
                            <td class="tcol1">2</td>
                            <td class="tcol2"><span><?php echo $data["rowTwoMenu2"];?></span></td>
                            <td class="tcol3"><span><?php echo $data["rowTwoPrice2"];?></span></td>
                        </tr>
                        <?php };
                        ?>

                        <?php
                        if($data["rowTwoMenu3"] > NULL){
                        ?>
                        <tr>
                            <td class="tcol1">3</td>
                            <td class="tcol2"><span><?php echo $data["rowTwoMenu3"];?></span></td>
                            <td class="tcol3"><span><?php echo $data["rowTwoPrice3"];?></span></td>
                        </tr>
                        <?php };
                        ?>

                        <?php
                        if($data["rowTwoMenu4"] > NULL){
                        ?>
                        <tr>
                            <td class="tcol1">4</td>
                            <td class="tcol2"><span><?php echo $data["rowTwoMenu4"];?></span></td>
                            <td class="tcol3"><span><?php echo $data["rowTwoPrice4"];?></span></td>
                        </tr>
                        <?php };
                        ?>
                        </tbody>
                        </tr>



                        <?php

                        if($data["rowThrMenu1"] > NULL){
                            ?>
                        <tr>
                            <tbody style="border-top: 2px solid #000000;">
                        <tr>
                            <td class="tcol1">1</td>
                            <td class="tcol2"><?php echo $data["rowThrMenu1"];?></td>
                            <td class="tcol3"><?php echo $data["rowThrPrice1"];?></td>
                            <td class="tcol4" rowspan="4" style="text-align: center;"><?php echo $data["rowThrNote"];?></td>
                        </tr>
                        <?php };
                        ?>

                        <?php
                        if($data["rowThrMenu2"] > NULL){
                        ?>
                        <tr>
                            <td class="tcol1">2</td>
                            <td class="tcol2"><span><?php echo $data["rowThrMenu2"];?></span></td>
                            <td class="tcol3"><span><?php echo $data["rowThrPrice2"];?></span></td>
                        </tr>
                        <?php };
                        ?>

                        <?php
                        if($data["rowThrMenu3"] > NULL){
                        ?>
                        <tr>
                            <td class="tcol1">3</td>
                            <td class="tcol2"><span><?php echo $data["rowThrMenu3"];?></span></td>
                            <td class="tcol3"><span><?php echo $data["rowThrPrice3"];?></span></td>
                        </tr>
                        <?php };
                        ?>

                        <?php
                        if($data["rowThrMenu4"] > NULL){
                        ?>
                        <tr>
                            <td class="tcol1">4</td>
                            <td class="tcol2"><span><?php echo $data["rowThrMenu4"];?></span></td>
                            <td class="tcol3"><span><?php echo $data["rowThrPrice4"];?></span></td>
                        </tr>
                        <?php };
                        ?>

                        </tbody>
                        </tr>


                    </table>
                </div>
                <!-- table -->

                <div class="d-flex flex-row mt-4">
                    <div class="col">
                        <div class="memo"><b><u>หมายเหตุ</u></b> : ราคานี้ไม่รวมค่าถุง<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ราคานี้ไม่รวมภาษีมูลค่าเพิ่ม<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ราคานี้รวมค่าจัดส่ง<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: กำหนดส่งสินค้าตามข้อตกลง หลังจากได้รับใบสั่งซื้อ<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: กำหนดยืนราคา 30 วันนับจากวันที่ในเอกสาร
                        </div>
                    </div>
                </div>
                <!-- comment -->
            </div>
        </div>

        <div class="row" style="margin-top: 10rem">
            <div class="d-flex flex-row">
                <div class="col-6 text-center">
                    <span><b>ลงชื่อผู้อนุมัติ</b><br><br><br><br> ...............................................</span><br><br>
                    <span>นาย อันวา ถนอมทรัพย์</span><br><br>
                    <span>กรรมการผู้จัดการ</span>
                </div>
                <div class="col-6 text-center">
                    <span><b>ลงชื่อลูกค้า</b><br><br><br><br> .............................................</span><br><br>
                    <span>(....................................................)</span><br><br>
                    <span>(....................................................)</span>
                </div>
            </div>
        </div>
        </div>
    </div>
</body>
</html>