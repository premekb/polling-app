<?php
/**
 * This file handles the data submitted from the form on the "create a new poll" page.
 */
require "polls_include.php";
include "validation_include.php";
session_start();
// if the form was not submitted properly or the user is not logged in
if (!isset($_POST["submit"]) || !isset($_SESSION["id"])){
    header("location: ../new_poll.php");
}

$answers = $_POST["answers"];
$answersCtr = count($answers);
$question = trim($_POST["question"]);
$uid = $_SESSION["id"];

// Save the form values in case of a failed form submission to be reused later.
$_SESSION["question"] = $question;

for ($i = 0; $i < count($answers); $i++){
    $key = "answer".($i + 1);
    $_SESSION[$key] = trim($answers[$i]);
}

// The question field is empty.
if (empty($question)){
    header("location: ../new_poll.php?error=emptyquestion&answers=$answersCtr");
    exit();
}

// The question is too long.
if (strlen($question) > 200){
    header("location: ../new_poll.php?error=toolongquestion&answers=$answersCtr");
    exit();
}

// Iterate over answers and check if they are in a valid form.
foreach ($answers as $value){
    $value = trim($value);

    // One of the answers field is empty or contains only spaces.
    if (strlen($value) == 0){
        header("location: ../new_poll.php?error=emptyanswer&answers=$answersCtr");
        exit();
    }
    
    // One of the answers is too long.
    if (strlen($value) > 100){
        header("location: ../new_poll.php?error=toolong&answers=$answersCtr");
        exit();
    }

    // Too many or not enough answers.
    if (count($answers) < 2 || count($answers) > 20){
        header("location: ../new_poll.php?error=wrongamountofanswers&answers=$answersCtr");
        exit();
    }
}

// Remove the session variables containing form values in case of a successful validation.
unset($_SESSION["question"]);
foreach($answers as $key => $value){
    unset($_SESSION[$key]);
}

for ($i = 0; $i < count($answers); $i++){
    $key = "answer".($i + 1);
    unset($_SESSION[$key]);
}
addPoll($answers, $question, $uid);
header("location: ../new_poll.php?error=pollcreated");
