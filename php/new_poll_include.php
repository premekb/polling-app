<?php
require "polls_include.php";
session_start();
if (!isset($_POST["submit"])){
    header("location: ../new_poll.php");
}

$keys = $_POST["answers"];
$question = htmlspecialchars(trim($_POST["question"]), ENT_QUOTES);
$uid = $_SESSION["id"];

// if (insufficientAnswers($keys)){
//     header("location: ../new_poll.php?error=insufficientanswers");
//     exit();
// }

// if (empty($question)){
//     header("location: ../new_poll.php?error=emptyquestion");
//     exit();
// }

foreach ($keys as $value){
    $value = htmlspecialchars(trim($value), ENT_QUOTES);
}

// if (asnwersLength($keys)){
//     header("location: ../new_poll.php?error=toolong");
//     exit();
// }


addPoll($keys, $question, $uid);
header("location: ../new_poll.php?error=pollcreated");
