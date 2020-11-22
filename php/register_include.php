<?php
    if (isset($_POST["submit"])){
        require "validation_include.php";
        require "db_connection.php";
        
        $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
        $password = $_POST["password"];
        $rpassword = $_POST["r_password"];
        $email = htmlspecialchars($_POST["email"], ENT_QUOTES);

        if (isEmpty($username, $password, $rpassword, $email)){
            header("location: ../register.php?error=emptyfield");
            exit();
        }

        if (dontMatch($password, $rpassword)){
            header("location: ../register.php?error=passwordsdontmatch");
            exit();
        }

        if (usernameWrong($username)){
            header("location: ../register.php?error=usernamewrong");
            exit();
        }

        if (passwordWrong($password)){
            header("location: ../register.php?error=passwordwrong");
            exit();
        }

        if (emailWrong($email)){
            header("location: ../register.php?error=emailwrong");
            exit();
        }

        if (usernameExists($username, $connection)){
            header("location: ../register.php?error=usernameexists");
            exit();
        }

        if (emailExists($email, $connection)){
            header("location: ../register.php?error=emailexists");
            exit();
        }

        createUser($username, $password, $email, $connection);
    }

    else{
        header("location: ../register.php");
    }