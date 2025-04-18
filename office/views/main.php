<?php
global $showPage, $datatable, $datatable2, $db, $loadTotal;
session_start();
include '../assets/db/db.php';
include "../assets/db/initDB.php";
require ("../assets/php/page_navigate.php");
date_default_timezone_set("Asia/Bangkok");

$myID = $_SESSION['user_id'];
$myRole = $_SESSION['role'];
$myName = $_SESSION['name'];
$myUser = $_SESSION['username'];
$myPass = $_SESSION['password'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ออฟฟิศเอ็มอาร์</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../assets/libs/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css">
<!--    <link rel="stylesheet" href="../assets/libs/datatablesBootstrap5/css/dataTables.bootstrap5.css">-->
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
  include "navBar.php";
  include "sideBar.php";
?>
  <div class="content-wrapper">
      <?php include $showPage; ?>
  </div>
  <aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <?php include "footer.php"; ?>
</div>

<script src="../assets/libs/jQuery-v3.7.1/jquery-3.7.1.min.js"></script>
<script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<!--<script type="text/javascript" src="../assets/libs/datatablesBootstrap5/js/dataTables2.2.2.js"></script>
<script type="text/javascript" src="../assets/libs/datatablesBootstrap5/js/dataTables.bootstrap5.js"></script>-->

<script>

</script>

</body>
</html>
