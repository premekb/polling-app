<?php
session_start();
include_once "polls_include.php";

if (isset($_POST["submit"]) && isset($_SESSION["id"])){
    $pid = $_POST["pid"];

    removePoll($_SESSION["id"], $pid);

    header("location: ../index.php");
}

else{
    header("location: ../index.php");
}