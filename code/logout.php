<?php
    require "./con_db.php";
    session_start();
    logout();
    header("Location: ./index.php");
    exit();
