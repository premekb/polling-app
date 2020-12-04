<?php
include_once "validation_include.php";
include_once "db_connection.php";
session_start();

// If the form was submitted properly and the user is logged in as admin.
// Then block the user.
if (isset($_POST["submit"]) && isset($_SESSION["id"]) && isAdmin($_SESSION["id"], $connection)){
    $block_uid = $_POST["uid"];
    blockUser($block_uid, $connection);
    header("location: ../index.php");
}

else{
    header("location: ../index.php");
}
