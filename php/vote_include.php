<?php
include "polls_include.php";
include "validation_include.php";
include "db_connection.php";

if (!isset($_POST["submit"])){
    header("location: ../index.php");
    exit();
}

session_start();
$pid = $_POST["pid"];

if (!isset($_SESSION["id"])){
    header("location: ../poll.php?id=$pid&error=notloggedin");
    exit();
}

if (!isset($_POST["vote"])){
    header("location: ../poll.php?id=$pid&error=nothingselected");
    exit();
}

$uid = $_SESSION["id"];
$answerId = $_POST["vote"];



if (!is_numeric($uid) or !is_numeric($answerId) or !is_numeric($pid)){
    header("location: ../poll.php?id=$pid&error=wronginput");
    exit();
}

if (userVoted($uid, $pid, $connection)){
    header("location: ../poll.php?id=$pid&error=alreadyvoted");
    exit();
}




vote($pid, $answerId, $uid);
header("location: ../poll.php?id=$pid&error=voted");
