<?php

function generatePollTable($page){
    // Generates page out of all polls.
    require "db_connection.php";
    if (!is_numeric($page)){
        $page = 1;
    }

    $page = (int)$page;

    if ($page > getPages()){
        $page = getPages();
    }

    if ($page < 1){
        $page = 1;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Poll name</th>";
    echo "<th>Date</th>";
    echo "<th>Created by</th>";
    echo "</tr>";
    $skip = ($page - 1) * 25;
    $query = "SELECT id, question, dateAdded, createdBy FROM polls ORDER BY id DESC LIMIT $skip, 25";
    $result = mysqli_query($connection, $query);
    $ctr = 0;
    while($row = mysqli_fetch_array($result)){
        $id = $row["id"];
        if ($ctr % 2 == 1) {
            echo "<tr class='odd'><td><a href='poll.php?id=$id' class='table'>";
        }
        else{
            echo "<tr><td><a href='poll.php?id=$id' class='table'>";
        }
        echo($row["question"]);
        echo "</a></td><td>";
        echo($row["dateAdded"]);
        echo "</td><td>";
        echo(uidToUsername($row["createdBy"]));
        echo "</td></tr>";
        $ctr += 1;
    }
    echo "</table>";   
}

function generateUserPollTable($page, $id){
    // Generates poll table based on user id.
    require "db_connection.php";
    if (!is_numeric($page)){
        $page = 0;
    }

    $page = (int)$page;

    if ($page > getPages()){
        $page = getPages();
    }

    if ($page < 0){
        $page = 0;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Poll name</th>";
    echo "<th>Date</th>";
    echo "<th>Created by</th>";
    echo "</tr>";
    $skip = ($page - 1) * 25;
    $query = "SELECT id, question, dateAdded, createdBy FROM polls WHERE createdBy=$id ORDER BY id DESC LIMIT $skip, 25";
    $result = mysqli_query($connection, $query);
    $ctr = 0;
    while($row = mysqli_fetch_array($result)){
        $id = $row["id"];
        if ($ctr % 2 == 1) {
            echo "<tr class='odd'><td><a href='poll.php?id=$id' class='table'>";
        }
        else{
            echo "<tr><td><a href='poll.php?id=$id' class='table'>";
        }
        echo($row["question"]);
        echo "</a></td><td>";
        echo($row["dateAdded"]);
        echo "</td><td>";
        echo($row["createdBy"]);
        echo "</td></tr>";
        $ctr += 1;
    }
    echo "</table>";  

}

function getPages(){
    // Returns the amount of total pages.
    require "db_connection.php";
    $query = "SELECT COUNT(1) FROM polls";
    $result = mysqli_query($connection, $query);
    return ceil(mysqli_fetch_array($result)["COUNT(1)"] / 25);
}

function printArrows($page){
    // Generates navigation arrows.
    $total = getPages();

    if (!is_numeric($page)){
        $page = 1;
    }

    $page = (int)$page;
    $nextPage = $page + 1;
    $previousPage = $page - 1;

    if ($page > $total){
        $page = $total;
    }

    if ($page < 1){
        $page = 1;
    }

    if ($page == 1){
        echo "<a href='index.php?page=$nextPage'>";
        echo "<img src='Icons/arrow_right_index.png' alt='' id='right_nav'>";
        echo "</a>";
    }

    elseif ($page == $total){
        echo "<a href='index.php?page=$previousPage'>";
        echo "<img src='Icons/arrow_left_index.png' alt='' id='left_nav'>";
        echo "</a>";
    }

    else {
        echo "<a href='index.php?page=$nextPage'>";
        echo "<img src='Icons/arrow_right_index.png' alt='' id='right_nav'>";
        echo "</a>";
        echo "<a href='index.php?page=$previousPage'>";
        echo "<img src='Icons/arrow_left_index.png' alt='' id='left_nav'>";
        echo "</a>";
    }
}

function removePoll($uid, $pid){
    // Function that removes poll, if the user trying to remove it is the creator or admin.
    require "db_connection.php";
    require "validation_include.php";
    if (isCreator($uid, $pid, $connection) or isAdmin($uid, $connection)){
        $query = "DELETE FROM polls WHERE id=?";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $query)){
            // Zmen mozna potom error
            die("stmt");
        }
        mysqli_stmt_bind_param($stmt, "s", $pid);
        mysqli_stmt_execute($stmt);
    }
}

function addPoll($answers, $question, $createdBy){
    // Adds a new poll into the database.
    require "db_connection.php";
    $query = "INSERT INTO polls (dateAdded, createdBy, question"; //pridat podle toho kolik mam answers
    $answersCtr = 0;
    $fString = "sis";
    $answersFiltered = array();
    foreach($answers as $answer){
        if (!empty($answer)){
            $answersCtr += 1;
            $query .= ",Answer$answersCtr";
            $fString .= "s";
            $answersFiltered[] = $answer;
        }
    }

    $query .= ") VALUES (?, ?, ?, ?";

    for ($i = 1; $i < $answersCtr; $i++){
        $query .= ",?";
    }

    $query .= ")";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $query)){
        // TODO
        echo "STMT";
        exit();
    }

    $date = date("yy-m-d");
    $createdBy = (int)$createdBy;

    mysqli_stmt_bind_param($stmt, $fString, $date, $createdBy, $question, ...$answersFiltered);
    mysqli_stmt_execute($stmt);
}

function generatePollInput($answers){
    // Generates input for for creating a new poll. Used when jscript not available.
    // If jscript is available. The links on images are removed in jscript.
    // TODO ONSUBMIT.
    if (!is_numeric($answers)){
        $answers = 3;
    }

    if ($answers < 2 or $answers > 20){
        $answers = 3;
    }

    echo "<h1>Create a new poll</h1>";
    echo "<form action='php/new_poll_include.php' method='POST' name='newpoll'>";
    echo "<label for='question'>Question</label><br>";
    echo "<input type='text' name='question' id='question'><br>";
    echo "<label for='answer1'>Answer 1</label><br>";
    echo "<input type='text' name='answers[]' id='answer1'><br>";
    echo "<label for='answer2'>Answer 2</label><br>";
    echo "<input type='text' name='answers[]' id='answer2'><br>";
    for ($i = 2; $i < $answers; $i++){
        $answerNumber = $i + 1;
        echo "<label for='answer$answerNumber'>Answer $answerNumber</label><br>";
        echo "<input type='text' name='answers[]' id='answer$answerNumber'><br>";
    }
    if ($answers < 20){
        $incrementedAnswers = $answers + 1;
        echo "<a href='new_poll.php?answers=$incrementedAnswers' id='plusphp'>";
        echo "<img src='Icons/plus_icon.png' alt='add another answer' id='addAnswer'>";
        echo "</a>";
    }

    if ($answers > 2){
        $decrementedAnswers = $answers - 1;
        echo "<a href='new_poll.php?answers=$decrementedAnswers' id='minusphp'>";
        echo "<img src='Icons/minus_icon.png' alt='remove an answer' id='removeAnswer'><br>";
        echo "</a>";
    }
    echo "<input type='submit' name='submit' value='Create a new poll'><br>";
    echo "</form>";
    echo "<script src='scripts/new_poll.js'></script>";
}

function generateVotingForm($row, $pid){
    $i = 1;
    if (!$row){
        echo "Poll doesn't exist.";
        die();
    }
    echo "<form id='voting_form' action='php/vote_include.php' method='POST'>"; // specifikuj potom kde to pujde
    while ($row["Answer$i"] != NULL){
        $answerText = $row["Answer$i"];
        echo "<input type='radio' name='vote' id='vote$i' value='$i'>";
        echo "<label for='vote$i' class='votelabel'>$answerText</label>";
        echo "<br>";
        $i++;
    }
    echo "<input type='hidden' name='pid' value='$pid'>";
    echo "<br>";
    echo "<input type='submit' name='submit' value='Vote'>";
    echo "</form>";
}

function generatePoll($pid){
    $row = getPollRow($pid);
    generatePollDescription($row);
    generatepollResults($row);
    echo "<div id='piechart'></div>";
    generateVotingForm($row, $pid);
}

function generatePollResults($row){
    // Generates list of results in plain HTML. It gets removed if JS is enabled.
    echo "<div id='jsoff'><ul>";
    for ($i = 1; $i <= 20; $i++){
        if (is_null($row["Answer$i"])){
            break;
        }
        $answer = $row["Answer$i"];
        $votes = $row["Votes$i"];
        echo "<li>$answer : $votes</li>";
    }
    echo "</ul></div>";
}

function generatePollDescription($row){
    $question = $row["question"];
    $createdBy = uidToUsername($row["createdBy"]);
    echo "<h1>$question</h1>";
    echo "<h2>Created by: $createdBy</h2>";
}

function getPollRow($pid){
    // Returns row from poll table if found.
    require "db_connection.php";
    if (!is_numeric($pid)){
        return false;
    }
    $query = "SELECT * FROM polls WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        return false;
    }
    $pid = (int)$pid;
    mysqli_stmt_bind_param($stmt, "i", $pid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    if (is_null($row)){
        return false;
    }
    return $row;
}

function vote($pid, $answerId, $uid){
    // Adds vote into the database. Pouzil jsem tohleto protoze stmt mi neslo.
    // POUZIJ TADY PAK MYSQLI REAL ESCAPE STRING!!!!!!!!!!!!
    require "db_connection.php";
    $pid = (int)$pid;
    $query = "UPDATE polls SET Votes$answerId = Votes$answerId + 1 WHERE id = $pid";
    mysqli_query($connection, $query);
    updateVotes($pid, $uid);
}

function updateVotes($pid, $uid){
    // Updates votes table, to prevent user from voting repeatedly.
    require "db_connection.php";
    $query = "INSERT INTO votes VALUES(?, ?)";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
    }

    $pid = (int)$pid;
    $uid = (int)$uid;

    mysqli_stmt_bind_param($stmt, "ii", $uid, $pid);
    mysqli_stmt_execute($stmt);
}

function getGoogleChartData($pid){
    // Echoes the poll data needed to create a google piechart from it.
    $row = getPollrow($pid);
    $title = $row["question"];
    $data = [["Task", $title]];
    for ($i = 1; $i <= 20; $i++){
        if (is_null($row["Answer$i"])){
            break;
        }
        array_push($data, [$row["Answer$i"],$row["Votes$i"]]);
    }
    echo json_encode($data);
}

function uidToUsername($uid){
    // Returns username for given user id.
    require "db_connection.php";
    $query = "SELECT username FROM users WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        return false;
    }
    $uid = (int)$uid;
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_array($result)["username"];
}