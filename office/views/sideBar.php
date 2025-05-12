<?php
global$showPage, $activeMenu, $coins;
$myID = $_SESSION['user_id'];
$myRole = $_SESSION['role'];
$myName = $_SESSION['name'];
$myUser = $_SESSION['username'];
$myPass = $_SESSION['password'];
$roleShow = ($myRole=='owner') ? 'เจ้าของ' : 'เซล';
//include ('assets/api/checkSession.php');
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="main.php" class="brand-link" style="text-decoration: none;">
        <img src="../assets/img/logo-login.png" alt="Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">ระบบหลังบ้าน</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar text-xs">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-between">
            <div class="d-flex">
                <div class="image">
<!--                    <a href="main.php?p=myProfile"><img src="dist/img/crews/--><?php //echo $_SESSION['userPic']; ?><!--" class="img-circle elevation-2" alt="User Image"></a>-->
                </div>
                <div class="info">
<!--                    <a href="main.php?p=myProfile" class="d-block">--><?php //echo $myName; ?><!-- - --><?php //echo $roleShow; ?><!--</a>-->
                    <a href="#" class="d-block" style="text-decoration: none;"><?php echo $myName .' : '. $roleShow; ?></a>
                </div>
            </div>
            <div class="info">
                <a href="logout.php?act=logout" class="d-block"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
       <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>-->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="main.php?p=dashboard" class="nav-link <?php echo $activeMenu["lv1"] == "dashboard" ? "active":""; ?>">
                        <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" fill="<?php echo $activeMenu["lv1"] == "dashboard" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                        <p>แดชบอร์ด</p>
                    </a>
                </li>

                <?php if ($myRole=="owner"){ ?>
                    <li class="nav-item">
                        <a href="main.php?p=report" class="nav-link <?php echo $activeMenu["lv1"] == "report" ? "active":""; ?>">
                            <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M160 80c0-26.5 21.5-48 48-48l32 0c26.5 0 48 21.5 48 48l0 352c0 26.5-21.5 48-48 48l-32 0c-26.5 0-48-21.5-48-48l0-352zM0 272c0-26.5 21.5-48 48-48l32 0c26.5 0 48 21.5 48 48l0 160c0 26.5-21.5 48-48 48l-32 0c-26.5 0-48-21.5-48-48L0 272zM368 96l32 0c26.5 0 48 21.5 48 48l0 288c0 26.5-21.5 48-48 48l-32 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48z" fill="<?php echo $activeMenu["lv1"] == "report" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                            <p>รายงานยอดขาย</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="main.php?p=quotation" class="nav-link <?php echo $activeMenu["lv1"] == "quotation" ? "active":""; ?>">
                            <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M64 464c-8.8 0-16-7.2-16-16L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm56 256c-13.3 0-24 10.7-24 24s10.7 24 24 24l144 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-144 0zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24l144 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-144 0z" fill="<?php echo $activeMenu["lv1"] == "quotation" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                            <p>ใบเสนอราคา</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $activeMenu["lv1"] == "setup" ? "menu-is-opening menu-open":""; ?>">
                        <a href="#" class="nav-link <?php echo $activeMenu["lv1"] == "setup" ? "active":""; ?>">
                            <svg class="nav-icon mr-2" id="Layer_1" data-name="Layer 1" height="1.5em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"  fill="<?php echo $activeMenu["lv1"] == "setup" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                            <p>
                                ตั้งค่าระบบ
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item pl-2">
                                <a href="main.php?p=users" class="nav-link <?php echo $activeMenu["lv2"] == "users" ? "active":""; ?>">
                                    <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"  fill="<?php echo $activeMenu["lv2"] == "users" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                                    <p>จัดการผู้ใช้งาน</p>
                                </a>
                            </li>
                            <li class="nav-item pl-2">
                                <a href="main.php?p=banks" class="nav-link <?php echo $activeMenu["lv2"] == "banks" ? "active":""; ?>">
                                    <svg class="nav-icon mr-2" height="1.3em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M243.4 2.6l-224 96c-14 6-21.8 21-18.7 35.8S16.8 160 32 160l0 8c0 13.3 10.7 24 24 24l400 0c13.3 0 24-10.7 24-24l0-8c15.2 0 28.3-10.7 31.3-25.6s-4.8-29.9-18.7-35.8l-224-96c-8-3.4-17.2-3.4-25.2 0zM128 224l-64 0 0 196.3c-.6 .3-1.2 .7-1.8 1.1l-48 32c-11.7 7.8-17 22.4-12.9 35.9S17.9 512 32 512l448 0c14.1 0 26.5-9.2 30.6-22.7s-1.1-28.1-12.9-35.9l-48-32c-.6-.4-1.2-.7-1.8-1.1L448 224l-64 0 0 192-40 0 0-192-64 0 0 192-48 0 0-192-64 0 0 192-40 0 0-192zM256 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64z" fill="<?php echo $activeMenu["lv2"] == "banks" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                                    <p>จัดการบัญชีธนาคาร</p>
                                </a>
                            </li>
                            <li class="nav-item pl-2">
                                <a href="main.php?p=lines" class="nav-link <?php echo $activeMenu["lv2"] == "lines" ? "active":""; ?>">
                                    <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M512 96c0 50.2-59.1 125.1-84.6 155c-3.8 4.4-9.4 6.1-14.5 5L320 256c-17.7 0-32 14.3-32 32s14.3 32 32 32l96 0c53 0 96 43 96 96s-43 96-96 96l-276.4 0c8.7-9.9 19.3-22.6 30-36.8c6.3-8.4 12.8-17.6 19-27.2L416 448c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0c-53 0-96-43-96-96s43-96 96-96l39.8 0c-21-31.5-39.8-67.7-39.8-96c0-53 43-96 96-96s96 43 96 96zM117.1 489.1c-3.8 4.3-7.2 8.1-10.1 11.3l-1.8 2-.2-.2c-6 4.6-14.6 4-20-1.8C59.8 473 0 402.5 0 352c0-53 43-96 96-96s96 43 96 96c0 30-21.1 67-43.5 97.9c-10.7 14.7-21.7 28-30.8 38.5l-.6 .7zM128 352a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zM416 128a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" fill="<?php echo $activeMenu["lv2"] == "lines" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                                    <p>จัดการสายลูกค้า > ลูกค้า</p>
                                </a>
                            </li>
                            <li class="nav-item pl-2">
                                <a href="main.php?p=category" class="nav-link <?php echo $activeMenu["lv2"] == "category" ? "active":""; ?>">
                                    <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M264.5 5.2c14.9-6.9 32.1-6.9 47 0l218.6 101c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 149.8C37.4 145.8 32 137.3 32 128s5.4-17.9 13.9-21.8L264.5 5.2zM476.9 209.6l53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 277.8C37.4 273.8 32 265.3 32 256s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0l152-70.2zm-152 198.2l152-70.2 53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 405.8C37.4 401.8 32 393.3 32 384s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0z" fill="<?php echo $activeMenu["lv2"] == "category" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                                    <p>จัดการประเภทสินค้า</p>
                                </a>
                            </li>
                            <li class="nav-item pl-2">
                                <a href="main.php?p=products" class="nav-link <?php echo $activeMenu["lv2"] == "products" ? "active":""; ?>">
                                    <svg class="nav-icon mr-2" height="1.3em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M416 0C400 0 288 32 288 176l0 112c0 35.3 28.7 64 64 64l32 0 0 128c0 17.7 14.3 32 32 32s32-14.3 32-32l0-128 0-112 0-208c0-17.7-14.3-32-32-32zM64 16C64 7.8 57.9 1 49.7 .1S34.2 4.6 32.4 12.5L2.1 148.8C.7 155.1 0 161.5 0 167.9c0 45.9 35.1 83.6 80 87.7L80 480c0 17.7 14.3 32 32 32s32-14.3 32-32l0-224.4c44.9-4.1 80-41.8 80-87.7c0-6.4-.7-12.8-2.1-19.1L191.6 12.5c-1.8-8-9.3-13.3-17.4-12.4S160 7.8 160 16l0 134.2c0 5.4-4.4 9.8-9.8 9.8c-5.1 0-9.3-3.9-9.8-9L127.9 14.6C127.2 6.3 120.3 0 112 0s-15.2 6.3-15.9 14.6L83.7 151c-.5 5.1-4.7 9-9.8 9c-5.4 0-9.8-4.4-9.8-9.8L64 16zm48.3 152l-.3 0-.3 0 .3-.7 .3 .7z" fill="<?php echo $activeMenu["lv2"] == "products" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                                    <p>จัดการสินค้า</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } //$myRole == "owner" ?>
                <?php if ($myRole=="sales"){ ?>
                    <li class="nav-item">
                        <a href="main.php?p=withdraw" class="nav-link <?php echo $activeMenu["lv1"] == "withdraw" ? "active":""; ?>">
                            <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M24 0C10.7 0 0 10.7 0 24S10.7 48 24 48l45.5 0c3.8 0 7.1 2.7 7.9 6.5l51.6 271c6.5 34 36.2 58.5 70.7 58.5L488 384c13.3 0 24-10.7 24-24s-10.7-24-24-24l-288.3 0c-11.5 0-21.4-8.2-23.6-19.5L170.7 288l288.5 0c32.6 0 61.1-21.8 69.5-53.3l41-152.3C576.6 57 557.4 32 531.1 32L360 32l0 102.1 23-23c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-64 64c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l23 23L312 32 120.1 32C111 12.8 91.6 0 69.5 0L24 0zM176 512a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm336-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0z" fill="<?php echo $activeMenu["lv1"] == "withdraw" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                            <p>เบิกสินค้า</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="main.php?p=withdrawHis" class="nav-link <?php echo $activeMenu["lv1"] == "withdrawHis" ? "active":""; ?>">
                            <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z" fill="<?php echo $activeMenu["lv1"] == "withdrawHis" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                            <p>รายการเบิกย้อนหลัง</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="main.php?p=payment" class="nav-link <?php echo $activeMenu["lv1"] == "payment" ? "active":""; ?>">
                            <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M163.9 136.9c-29.4-29.8-29.4-78.2 0-108s77-29.8 106.4 0l17.7 18 17.7-18c29.4-29.8 77-29.8 106.4 0s29.4 78.2 0 108L310.5 240.1c-6.2 6.3-14.3 9.4-22.5 9.4s-16.3-3.1-22.5-9.4L163.9 136.9zM568.2 336.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 485.5c-23.4 17.2-51.6 26.5-80.7 26.5L192 512 32 512c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l36.8 0 44.9-36c22.7-18.2 50.9-28 80-28l78.3 0 16 0 64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0-16 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l120.6 0 119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 384c0 0 0 0 0 0l-.9 0c.3 0 .6 0 .9 0z" fill="<?php echo $activeMenu["lv1"] == "payment" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                            <p>รับชำระเงิน</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="main.php?p=paymentHis" class="nav-link <?php echo $activeMenu["lv1"] == "paymentHis" ? "active":""; ?>">
                            <svg class="nav-icon mr-2" height="1.5em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 96C0 43 43 0 96 0L384 0l32 0c17.7 0 32 14.3 32 32l0 320c0 17.7-14.3 32-32 32l0 64c17.7 0 32 14.3 32 32s-14.3 32-32 32l-32 0L96 512c-53 0-96-43-96-96L0 96zM64 416c0 17.7 14.3 32 32 32l256 0 0-64L96 384c-17.7 0-32 14.3-32 32zM208 112l0 48-48 0c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l48 0 0 48c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-48 48 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-48 0 0-48c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" fill="<?php echo $activeMenu["lv1"] == "paymentHis" ? "#FB8500":"#a7acb6"; ?>"/></svg>
                            <p>ประวัติรับชำระ</p>
                        </a>
                    </li>
                <?php } //$myRole == "sales" ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>