<?php
    require "./con_db.php";
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $json_result = register($username, $password, $email);
    $result = json_decode($json_result);

    if ($result->register == "register success") {
        header("Location: ./login.php");
    }
    else {
        echo $result->register;
    }
