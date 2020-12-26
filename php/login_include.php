<?php
/**
 * This file handles the data submitted from the login form.
 */
    if (isset($_POST["submit"])){
        require "db_connection.php";
        require "validation_include.php";
        
        $username = $_POST["username"];
        $password = $_POST["password"];

        login($username, $password, $connection);
    }
    else{
        header("location: ../login.php");
    }
