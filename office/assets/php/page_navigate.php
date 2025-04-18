<?php
session_start();
$myID = $_SESSION['user_id'];
$myRole = $_SESSION['role'];

$menuPage = $_REQUEST["p"] ?? "dashboard";

if ($myRole == "owner") {
    if ($menuPage == "dashboard") {
        $showPage = "dashboard_owner.php";
        $activeMenu["lv1"] = "dashboard";
        $activeMenu["lv2"] = "";
    }else if ($menuPage == "users") {
        $showPage = "user_list.php";
        $activeMenu["lv1"] = "setup";
        $activeMenu["lv2"] = "users";
    }else if ($menuPage == "banks") {
        $showPage = "bank_list.php";
        $activeMenu["lv1"] = "setup";
        $activeMenu["lv2"] = "banks";
    }else if ($menuPage == "lines") {
        $showPage = "route_list.php";
        $activeMenu["lv1"] = "setup";
        $activeMenu["lv2"] = "lines";
    }else if ($menuPage == "products") {
        $showPage = "product_list.php";
        $activeMenu["lv1"] = "setup";
        $activeMenu["lv2"] = "products";
    }else if ($menuPage == "category") {
        $showPage = "product_category_list.php";
        $activeMenu["lv1"] = "setup";
        $activeMenu["lv2"] = "category";
    }else if ($menuPage == "report") {
        $showPage = "report_sales.php";
        $activeMenu["lv1"] = "report";
        $activeMenu["lv2"] = "";
    }else if ($menuPage == "quotation") {
        $showPage = "quotation_form.php";
        $activeMenu["lv1"] = "quotation";
        $activeMenu["lv2"] = "";
    }
}else if ($myRole=="sales") {
    if ($menuPage == "dashboard") {
        $showPage = "dashboard.php";
        $activeMenu["lv1"] = "dashboard";
        $activeMenu["lv2"] = "";
    }else if ($menuPage == "withdraw") {
        $showPage = "withdraw_create.php";
        $activeMenu["lv1"] = "withdraw";
        $activeMenu["lv2"] = "";
    }else if ($menuPage == "withdrawHis") {
        $showPage = "withdraw_list.php";
        $activeMenu["lv1"] = "withdrawHis";
        $activeMenu["lv2"] = "";
    }else if ($menuPage == "payment") {
        $showPage = "payment_create.php";
        $activeMenu["lv1"] = "payment";
        $activeMenu["lv2"] = "";
    }else if ($menuPage == "paymentHis") {
        $showPage = "payment_list.php";
        $activeMenu["lv1"] = "paymentHis";
        $activeMenu["lv2"] = "";
    }
}