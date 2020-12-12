<?php

function getError($errorMessage){
    switch ($errorMessage){
        case "emptyfield":
            return "<p>You need to fill in all the form fields.</p>";
        case "passwordsdontmatch":
            return "<p>The passwords you filled in don't match.</p>";
        case "usernamewrong":
            return"<p>The length of your username needs to be 3 - 30 characters and can only contain letters and digits.</p>";
        case "passwordwrong":
            return "<p>Your password needs to be 6 - 100 characters.</p>";
        case "emailwrong":
            return "<p>The e-mail address is not valid.</p>";
        case "usernameexists":
            return "<p>The username is already taken.</p>";
        case "emailexists":
            return "<p>The e-mail address is already taken.</p>";
        case "usercreated":
            return "<p>Your account was created!</p>";
        case "loginfailed":
            return "<p>Wrong credentials entered.</p>";
        case "notloggedin":
            return "<p>You need to be logged in to perform this action.</p>";
        case "nothingselected":
            return "<p>You need to select an answer.</p>";
        case "wronginput":
            return "<p>Something went wrong.</p>";
        case "alreadyvoted":
            return "<p>You have already voted.</p>";
        case "voted":
            return "<p>You have successfully casted your vote.</p>";
        case "emptyquestion":
            return "<p>The question field must be filled in.</p>";
        case "toolongquestion":
            return "<p>The question is too long. It cannot be longer than 200 characters.</p>";
        case "emptyanswer":
            return "<p>You left one of the answer fields empty.</p>";
        case "toolong":
            return "<p>The answer is too long. It cannot be longer than 100 characters.</p>";
        case "wrongamountofanswers":
            return "<p>Too number of answers must be within this range 2 - 20.</p>";
        case "pollcreated":
            return "<p>The poll was successfully created.</p>";

        return "<p>Something went wrong.</p>";
        
    }

    return "<p>Unknown error</p>";
}