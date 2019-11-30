<?php
    require "./con_db.php";
    $new_password = $_POST['new_password'];
    $json_message = change_password($new_password);
    $message = json_decode($json_message);
    if ($message->change_password == "change success") {
        header("Location: ./logout.php");
    }
    else{
        echo $message->change_password;
    }
