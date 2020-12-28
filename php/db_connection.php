<?php
$dbusername = "";
$dbpassword = "";
$dbname = "";
$dbhost = ";

$connection = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);

if (!$connection){
    die("Connection failed".mysqli_connect_error());
}
