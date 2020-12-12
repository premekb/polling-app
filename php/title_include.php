<?php

function printTitle($title){
    if ($title == "index.php"){
        return "Polls";
        exit();
    }

    if ($title == "new_poll.php"){
        return "Create a new poll";
        exit();
    }

    if ($title == "register.php"){
        return "Register";
        exit();
    }

    if ($title == "login.php"){
        return "Log in";
        exit();
    }

    if ($title == "poll.php"){
        return "Vote";
        exit();
    }
    return "Unknown page";
}