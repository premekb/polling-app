<?php
/**
 * Returns the browser title based on the current page.
 * 
 * @param string $title Name of the page.
 * 
 * @return string
 */
function printTitle($title){
    switch($title){
        case "index.php":
            return "Polls";
        case "new_poll.php":
            return "Create a new poll";
        case "register.php":
            return "Register";
        case "login.php":
            return "Log in";
        case "poll.php":
            return "Vote";
    }
    return "Unknown page";
}