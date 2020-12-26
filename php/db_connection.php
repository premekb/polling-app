<?php
$dbusername = "w264019_polls";
$dbpassword = "fWRk9gjU";
$dbname = "d264019_polls";
$dbhost = "md104.wedos.net";

$connection = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);

if (!$connection){
    die("Connection failed".mysqli_connect_error());
}