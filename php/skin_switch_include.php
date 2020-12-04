<?php
// Create the skin cookie if it doesn't exist.
if(!isset($_COOKIE["skin"])){
    setcookie("skin", "light", time() + (86400 * 1000), "/~belkapre", ".toad.cz");
    }

// Switch the skin based on current skin cookie value.
if ($_COOKIE["skin"] == "light"){
    setcookie("skin", "dark", time() + (86400 * 1000), "/~belkapre", ".toad.cz");
}

else{
    setcookie("skin", "light", time() + (86400 * 1000), "/~belkapre", ".toad.cz");
}

header("location: ../index.php");
?>