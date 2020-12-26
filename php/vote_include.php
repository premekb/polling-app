<?php
/**
 * This file handles the voting form on the poll.php page.
 */
include "polls_include.php";
include "validation_include.php";
include "db_connection.php";

if (!isset($_POST["submit"])){
    header("location: ../index.php");
    exit();
}

session_start();
$pid = $_POST["pid"];

// the user is not logged in
if (!isset($_SESSION["id"])){
    header("location: ../poll.php?id=$pid&error=notloggedin");
    exit();
}

// the user did not select any answer
if (!isset($_POST["vote"])){
    header("location: ../poll.php?id=$pid&error=nothingselected");
    exit();
}

$uid = $_SESSION["id"];
$answerId = $_POST["vote"];

// the user probably manually changed the HTML
if (!is_numeric($uid) or !is_numeric($answerId) or !is_numeric($pid)){
    header("location: ../poll.php?id=$pid&error=wronginput");
    exit();
}

// the user has already voted
if (userVoted($uid, $pid, $connection)){
    header("location: ../poll.php?id=$pid&error=alreadyvoted");
    exit();
}

// the form was submitted properly, cast the vote
vote($pid, $answerId, $uid);
header("location: ../poll.php?id=$pid&error=voted");
