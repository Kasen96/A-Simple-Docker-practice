<?php
session_start();
if (isset($_SESSION['user_id'])) {
    require "./html/login_header.html";
}
else {
    require "./html/no_login_header.html";
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>登录界面</title>
</head>
<body>
<div>
    <br><br><br>
    <h3 align="center">登录</h3>
    <div align="center">
        <form action="login_handle.php" method="post" id="login">
            <div>
                <input type="text" id="input_name" name="username" placeholder="请输入姓名"/>
            </div>
            <div>
                <input type="password" id="input_password" name="password" placeholder="请输入密码"/>
            </div>
            <div>
                <input type="submit" value="登录" />
            </div>
        </form>
    </div>
</div>
</body>
</html>
