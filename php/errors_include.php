<?php

function printError($errorMessage){
    if ($errorMessage == "emptyfield"){
        return "<p>You need to fill in all the form fields.</p>";
    }

    if ($errorMessage == "passwordsdontmatch"){
        return "<p>The passwords you filled in don't match.</p>";
    }

    if ($errorMessage == "usernamewrong"){
        return "<p>The length of your username needs to be 3 - 30 characters and can only contain letters and digits.</p>";
    }

    if ($errorMessage == "emptyfield"){
        return "<p>You need to fill in all the form fields.</p>";
    }

    if ($errorMessage == "passwordwrong"){
        return "<p>Your password needs to be 6 - 100 characters.</p>";
    }

    if ($errorMessage == "emailwrong"){
        return "<p>The e-mail address is not valid.</p>";
    }

    if ($errorMessage == "usernameexists"){
        return "<p>The username is already taken.</p>";
    }

    if ($errorMessage == "emailexists"){
        return "<p>The e-mail address is already taken.</p>";
    }

    if ($errorMessage == "usercreated"){
        return "<p>Your account was created!</p>";
    }

    if ($errorMessage == "loginfailed"){
        return "<p>Wrong credentials entered.</p>";
    }

    return "<p>Unknown error</p>";
}