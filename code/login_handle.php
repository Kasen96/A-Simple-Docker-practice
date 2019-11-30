<?php
    require "./con_db.php";
    $username = $_POST['username'];
    $password = $_POST['password'];
    $json_result = login($username, $password);
    $result = json_decode($json_result);

    if ($result->login == "login success") {
        header("Location: ./list.php");
    }
    else {
        echo $result->login;
    }


