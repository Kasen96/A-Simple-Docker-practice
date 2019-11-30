<?php

    function connect_mysql() {
        $sql_url = "mysql-db";
        $sql_user = "docker";
        $sql_password = "123456";
        $sql_database = "docker_todolist";
        $dbc = mysqli_connect($sql_url, $sql_user, $sql_password, $sql_database);

        if (!$dbc) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit();
        }

        mysqli_set_charset($dbc, 'utf8');
        return $dbc;
    }

    function register($username, $password, $email) {
        $dbc = connect_mysql();
        if ($dbc) {
            $q = 'insert into docker_todolist.user(name, password, email) values (?, ?, ?)';
            $stmt = mysqli_prepare($dbc, $q);

            if (!$stmt) {
                echo "Error of mysqli_prepare(): " . mysqli_error($dbc) . PHP_EOL;
                exit();
            }

            mysqli_stmt_bind_param($stmt, 'sss', $name_sql, $password_sql, $email_sql);
            $name_sql = $username;
            $password_sql = $password;
            $email_sql = $email;
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) == 1) {
                $message['register'] = 'register success';
                return json_encode($message);
            }
            else {
                $message['register'] = 'register fail';
                return json_encode($message);
            }
            mysqli_stmt_close($stmt);
            mysqli_close($dbc);
        }
        else {
            die('Could not connect" ' . mysqli_error($dbc));
        }

        return null;
    }

    function login($username, $password) {
        session_start();
        $dbc = connect_mysql();
        $user_id = null;
        if ($dbc) {
            $q = 'select user_id from docker_todolist.user where name = ? and password = ?';
            $stmt = mysqli_prepare($dbc, $q);

            if (!$stmt) {
                echo "Error of mysqli_prepare(): " . mysqli_error($dbc) . PHP_EOL;
                exit();
            }

            mysqli_stmt_bind_param($stmt, 'ss', $name_sql, $password_sql);
            $name_sql = $username;
            $password_sql = $password;
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $user_id_sql);
            if (mysqli_stmt_affected_rows($stmt) == 1) {
                while (mysqli_stmt_fetch($stmt)) {
                    $user_id = $user_id_sql;
                }
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $name_sql;
                $_SESSION['password'] = $password_sql;
                $message['login'] = 'login success';
                return json_encode($message);
            }
            else {
                $message['login'] = 'login error';
                return json_encode($message);
            }
            mysqli_stmt_close($stmt);
            mysqli_close($dbc);
        }
        else {
            die('Could not connect: ' . mysqli_error($dbc));
        }

        return null;
    }

    /*
    function check_name($username) {
        $dbc = connect_mysql();
        if ($dbc) {
            $q = 'select * from docker_todolist.user where name = ?';
            $stmt = mysqli_prepare($dbc, $q);
            mysqli_stmt_bind_param($stmt, 's', $name_sql);
            $name_sql = $username;
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_affected_rows($stmt) == 1) {
                $message['check_name'] = 'name exist';
                return json_encode($message);
            }
            else {
                $message['check_name'] = 'name does not exist';
                return json_encode($message);
            }
            mysqli_stmt_close($stmt);
            mysqli_close($dbc);
        }
        else {
            die('Could not connect: ' . mysqli_error($dbc));
        }

        return null;
    }
    */

    function logout() {
        session_destroy();
        setcookie('phpsessid', '', time() - 3600);
    }


    function check_password($old_password) {
        session_start();
        $password = $_SESSION['password'];
        if ($password != $old_password) {
            $message['check_password'] = 'password wrong';
            return json_encode($message);
        }
        else {
            $message['check_password'] = 'password correct';
            return json_encode($message);
        }
    }

    function change_password($new_password) {
        session_start();
        $dbc = connect_mysql();
        $session_user_id = $_SESSION['user_id'];
        if ($dbc) {
            $q = 'update docker_todolist.user set password = ? where user_id = ?';
            $stmt = mysqli_prepare($dbc, $q);

            if (!$stmt) {
                echo "Error of mysqli_prepare(): " . mysqli_error($dbc) . PHP_EOL;
                exit();
            }

            mysqli_stmt_bind_param($stmt, 'si', $password_sql, $user_id_sql);
            $password_sql = $new_password;
            $user_id_sql = $session_user_id;
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) == 1){
                $message['change_password'] = 'change success';
                return json_encode($message);
            }
            else {
                $message['change_password'] = 'change fail';
                return json_encode($message);
            }
            mysqli_stmt_close($stmt);
            mysqli_close($dbc);
        }
        else {
            die('Could not connect: ' . mysqli_error($dbc));
        }

        return null;
    }

    function show_list($user_id_from_user) {
        $dbc = connect_mysql();
        $q = 'select content, list_id from docker_todolist.list where user_id = ?';
        $stmt = mysqli_prepare($dbc, $q);

        if (!$stmt) {
            echo "Error of mysqli_prepare(): " . mysqli_error($dbc) . PHP_EOL;
            exit();
        }

        mysqli_stmt_bind_param($stmt, 'i', $user_id_sql);
        $user_id_sql = $user_id_from_user;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $content, $list_id);
        while (mysqli_stmt_fetch($stmt)) {
            $list['content'] = $content;
            $list['list_id'] = $list_id;
            $list_merge[] = $list;
        }
        return json_encode($list_merge);
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
    }

    function add_list($form_content) {
        session_start();
        $user_id_from_session = $_SESSION['user_id'];
        $dbc = connect_mysql();
        $q = 'insert into docker_todolist.list (content, user_id) values (?, ?)';
        $stmt = mysqli_prepare($dbc, $q);

        if (!$stmt) {
            echo "Error of mysqli_prepare(): " . mysqli_error($dbc) . PHP_EOL;
            exit();
        }

        mysqli_stmt_bind_param($stmt, 'si', $content_sql, $user_id_sql);
        $content_sql = $form_content;
        $user_id_sql = $user_id_from_session;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
    }

    function delete_list($form_list_id) {
        $dbc = connect_mysql();
        $q = 'delete from docker_todolist.list where list_id = ?';
        $stmt = mysqli_prepare($dbc, $q);

        if (!$stmt) {
            echo "Error of mysqli_prepare(): " . mysqli_error($dbc) . PHP_EOL;
            exit();
        }

        mysqli_stmt_bind_param($stmt, 'i', $list_id_sql);
        $list_id_sql = $form_list_id;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
    }

