<?php
/**
 * This file handles the POST request from the "delete the user" button on a poll page.
 */
session_start();
include_once "polls_include.php";

if (isset($_POST["submit"]) && isset($_SESSION["id"])){
    $pid = $_POST["pid"];

    removePoll($_SESSION["id"], $pid);

    header("location: ../index.php");
}

else{
    /**
     * The location is set to the index.php because the user is not supposed to se the button at all,
     * if he doesn't have the permission to delete. Therefore the request can only be sent with a malicious intent.
     */
    header("location: ../index.php");
}