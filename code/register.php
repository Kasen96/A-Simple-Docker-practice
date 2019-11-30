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
    <title>注册界面</title>
</head>
<body>
<div>
    <br><br><br>
    <h3 align="center">注册</h3>
    <div align="center">
        <form action="register_handle.php" method="post" id="register">
            <div>
                <input type="text" id="input_name" name="username" placeholder="请输入姓名"/>
            </div>
            <div>
                <input type="password" id="input_password" name="password" placeholder="请输入密码"/>
            </div>
            <div>
                <input type="password" id="confirm_password" placeholder="请确认密码"/>
            </div>
            <div>
                <input type="text" id="input_email" name="email" placeholder="请输入邮箱">
            </div>
            <div>
                <input type="submit" value="注册" />
            </div>
        </form>
    </div>
</div>
</body>
</html>
