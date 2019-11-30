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
    <title>更改密码</title>
</head>
<body>
<div>
    <br><br><br>
    <h3 align="center">修改密码</h3>
    <div align="center">
        <form action="change_password_handle.php" method="post" id="change_password">
            <div>
                <input type="password" id="old_password" name="old_password" placeholder="请输入原来的密码"/>
            </div>
            <div>
                <input type="password" id="new_password" name="new_password" placeholder="请输入新密码"/>
            </div>
            <div>
                <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="请确认新密码">
            </div>
            <div>
                <input type="submit" value="确认修改密码" />
            </div>
        </form>
    </div>
</div>
</body>
</html>
