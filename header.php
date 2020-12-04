<?php
    include_once "php/title_include.php";
    include "php/validation_include.php";
    include "php/db_connection.php";
    session_start();
    // If the user is blocked, log him out. And alert him via JS.
    if (isset($_SESSION["id"]) && isBlocked($_SESSION["id"], $connection)){
        session_unset();
        session_destroy();
        echo "<script>alert('Your account was blocked.')</script>";
    }

    // If the user is logged in, deny access to the login and registration page.
    $curr_page = basename($_SERVER['PHP_SELF']);
    if(isset($_SESSION["id"]) && ($curr_page == "login.php" || $curr_page == "register.php")){
        header("location: index.php");
    }

    // Create the skin cookie if it doesn't exist.
    if(!isset($_COOKIE["skin"])){
    setcookie("skin", "light", time() + (86400 * 1000), "/~belkapre", ".toad.cz");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- If the user has disabled cookies, or on the first visit, set the skin to light as default.-->
    <link rel="stylesheet" href=<?php if(isset($_COOKIE["skin"])){echo "'css/styles_".$_COOKIE["skin"].".css'";} else{echo "'css/styles_light.css'";}?>>
    <link rel="stylesheet" media="print" href="css/styles_print.css">
    <title><?php echo printTitle(basename($_SERVER['PHP_SELF']))?></title>
</head>
<body>
 <header>
    <nav id="top_nav">
        <a href="index.php">Main site</a> <!-- Logo? -->
        <?php
        if (isset($_SESSION["id"])){
            echo "<a href='new_poll.php'>Create a new poll</a>
                <a href='my_polls.php'>My polls</a>
                <a href='profile.php'>My profile</a>
                <a href='logout.php'>Logout</a>";
        }

        else{
            echo "<a href='register.php'>Register</a>
                <a href='login.php'>Login</a>";
        }

        echo "<span id='skin'><a href='php/skin_switch_include.php'>";
        // Print light mode or dark mode based on skin cookie.
        if (!isset($_COOKIE["skin"]) || $_COOKIE["skin"] == "light"){
            echo "Dark mode";
        }

        else{
            echo "Light mode";
        }
        echo "</a></span>";
        ?>
    </nav>
</header>