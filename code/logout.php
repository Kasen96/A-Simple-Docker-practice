<?php
    session_start();
    require "./con_db.php";
    logout();
    header("Location: ./index.php");
    exit();
