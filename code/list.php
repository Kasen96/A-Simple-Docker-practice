<?php
    require "./html/login_header.html";
    require "./con_db.php";
    session_start();
    $count = 0;
    $user_id = $_SESSION['user_id'];
    $list_json = show_list($user_id);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>计划界面</title>
</head>
<body>
<div>
    <br><br><br>
    <div>
        <h3 align="center"><?php echo $_SESSION['username'] ?>，欢迎来到你的计划列表</h3>
        <br />
        <form action="list_handle.php?act=add" method="post">
            内容：<textarea name="content" cols="30" rows="3"></textarea>
            <br>
            <input type="submit" value="添加" />
        </form>
    </div>
    <div>
        <h1 align="center">计划列表</h1>
        <table>
            <tr>
                <td></td>
                <td>内容</td>
                <td>操作</td>
                <td></td>
            </tr>

            <?php
                $list = json_decode($list_json);
                foreach ($list as $key => $value) {
                    if ($value) {
                        echo '<tr><td>'.++$count.'</td>';
                        echo '
                              <td>'.$value->content.'</td>
                              <td>
                                  <div>
                                       <ul>
                                           <li>
                                               <a href="list_handle.php?act=delete&list_id='.$value->list_id.'">删除计划</a>
                                           </li>
                                       </ul>
                                  </div>
                              </td></tr>';
                    }
                }
            ?>

        </table>
    </div>
</div>
</body>
</html>
