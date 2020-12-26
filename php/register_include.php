<?php
/**
 * This file handles the data submitted from the registration form.
 */
    if (isset($_POST["submit"])){
        require "validation_include.php";
        require "db_connection.php";
        
        $username = $_POST["username"];
        $password = $_POST["password"];
        $rpassword = $_POST["r_password"];
        $email = $_POST["email"];

        // Start session to save the form values in case of failed form submission.
        session_start();
        $_SESSION["r_username"] = $username;
        $_SESSION["r_email"] = $email;

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

        // The data is in correct format. Remove the saved values.
        unset($_SESSION["r_username"]);
        unset($_SESSION["r_email"]);
        createUser($username, $password, $email, $connection);
    }

    else{
        header("location: ../register.php");
    }