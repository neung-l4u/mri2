<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$remember = $_COOKIE['remember_remember'] ?? '';
$username = $_COOKIE['remember_username'] ?? '';
$password = $_COOKIE['remember_password'] ?? '';
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/index.css" rel="stylesheet">
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-primary"><img src="../assets/img/logo-web-144.png" alt="logo"></h4>
        <p class="text-primary small">เข้าสู่ระบบ</p>
    </div>
    <form id="loginForm">
        <div class="mb-3">
            <label for="username" class="form-label">เบอร์โทรศัพท์ หรือ อีเมล</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">รหัสผ่าน</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁</button>
            </div>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" <?php echo ($remember) ? 'checked': ''; ?> >
            <label class="form-check-label" for="remember">จดจำฉันไว้</label>
        </div>
        <div class="d-grid">
            <button type="button" onclick="login();" class="btn btn-primary">เข้าสู่ระบบ</button>
        </div>
        <div id="loginAlert" class="alert alert-danger mt-3 d-none" role="alert">
            ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง
        </div>
    </form>
</div>

<script src="../assets/libs/jQuery-v3.7.1/jquery-3.7.1.min.js"></script>
<script>
    let payload = {};
    let res = {};
    res.msg = undefined;

    const username = $('#username');
    const password = $('#password');
    const remember = $('#remember');
    const loginAlert = $('#loginAlert');

    function togglePassword() {
        const pwd = document.getElementById('password');
        pwd.type = pwd.type === 'password' ? 'text' : 'password';
    }

    function login() {
        payload.act = "login";
        payload.username = username.val();
        payload.password = password.val();
        payload.remember = remember.is(':checked'); ;

        loginAlert.addClass('d-none');

        const chkLogin = $.ajax({
            url: "../assets/php/login.php",
            method: 'POST',
            async: false,
            cache: false,
            dataType: 'json',
            data: payload
        });

        chkLogin.done(function (res) {
            console.log(res);
            if(res.result === "success"){
                loginAlert.html(res.msg).removeClass('alert-danger').addClass('alert-success').removeClass('d-none');
                if(res.role === "sales"){ setTimeout(() => { location.replace('main.php?p=dashboard'); }, 500); }
                else if(res.role === "owner"){ setTimeout(() => { location.replace('main.php?p=dashboard'); }, 500); }

            }else if(res.result === "fail"){
                loginAlert.html(res.msg).removeClass('alert-success').addClass('alert-danger').removeClass('d-none');
            }
        });

        chkLogin.fail(function (xhr, status, error) {
            console.log("ajax get Price fail!!");
            console.log(status + ": " + error);
        });
    }//login

    $(()=>{

    });//ready
</script>
</body>
</html>
