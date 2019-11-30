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
    <title>首页</title>
</head>
<body>

</body>
</html>
