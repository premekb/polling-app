<?php
$dbusername = "belkapre";
$dbpassword = "webove aplikace";
$dbname = "belkapre";
$dbhost = "localhost";

$connection = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);

if (!$connection){
    die("Connection failed");
}


