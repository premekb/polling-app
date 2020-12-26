<?php
/**
 * This file switches the skin cookie. The user is redirected here after clicking on the switch skin link
 * in the nav menu
 */

// Create the skin cookie if it doesn't exist.
if(!isset($_COOKIE["skin"])){
    setcookie("skin", "light", time() + (86400 * 1000), "/polls");
    }

// Switch the skin based on current skin cookie value.
if ($_COOKIE["skin"] == "light"){
    setcookie("skin", "dark", time() + (86400 * 1000), "/polls");
}

else{
    setcookie("skin", "light", time() + (86400 * 1000), "/polls");
}

header("location: ../index.php");
?>