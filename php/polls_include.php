<?php
/**
 * Fetches data from the DB, and generates a table on the index page.
 * 
 * @param $page A number of the page to be generated.
 * @param $rows Amount of rows to be generated on the page. Default is 25.
 * 
 * @return void
 */
function generatePollTable($page, $rows = 25){
    require "db_connection.php";
    if (!is_numeric($page)){
        $page = 1;
    }

    $page = (int)$page;

    if ($page > getPages($rows)){
        $page = getPages($rows);
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
    // Skip variable contains the number of pages that should be skipped.
    $skip = ($page - 1) * $rows;
    $query = "SELECT id, question, dateAdded, createdBy FROM polls ORDER BY id DESC LIMIT ?, ?";
    $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $query)){
            die("stmt");
        }
    mysqli_stmt_bind_param($stmt, "ii", $skip, $rows);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ctr = 0;
    while($row = mysqli_fetch_array($result)){
        // No need to escape id, it is generated as primary key by the DB.
        $id = $row["id"];
        if ($ctr % 2 == 1) {
            echo "<tr class='odd'><td><a href='poll.php?id=$id' class='table'>";
        }
        else{
            echo "<tr><td><a href='poll.php?id=$id' class='table'>";
        }
        echo(htmlspecialchars($row["question"], ENT_QUOTES));
        echo "</a></td><td>";
        echo(htmlspecialchars($row["dateAdded"], ENT_QUOTES));
        echo "</td><td>";
        echo(htmlspecialchars(uidToUsername($row["createdBy"], ENT_QUOTES)));
        echo "</td></tr>";
        $ctr += 1;
    }
    echo "</table>";   
}

/**
 * Returns the amount of pages of polls in the DB based on the rows parameter.
 * 
 * @param $rows Amount of rows on one page. Default is 25.
 * 
 * @return int
 */
function getPages($rows = 25){
    require "db_connection.php";
    $query = "SELECT COUNT(1) FROM polls";
    $result = mysqli_query($connection, $query);
    return ceil(mysqli_fetch_array($result)["COUNT(1)"] / $rows);
}

/**
 * Generates navigation arrows under the table on the index page.
 * 
 * @param $page Page of the table.
 * 
 * @return void
 */
function printArrows($page){
    $total = getPages();

    // If the user submits nonsensical value in the GET parameter.
    if (!is_numeric($page)){
        $page = 1;
    }

    $page = (int)$page;
    $nextPage = $page + 1;
    $previousPage = $page - 1;

    // If the user submits page number, that is too large.
    if ($page > $total){
        $page = $total;
    }

    // If the user submits a negative page number.
    if ($page < 1){
        $page = 1;
    }

    // Don't generate any arrows if there is only one page.
    if ($total != 1)
    {
        // Generate only the right arrow if the user is on the first page.
        if ($page == 1){
            echo "<a href='index.php?page=$nextPage'>";
            echo "<img src='Icons/arrow_right_index.png' alt='' id='right_nav'>";
            echo "</a>";
        }

        // Generate only the left arrow if the user is on the first page.
        elseif ($page == $total){
            echo "<a href='index.php?page=$previousPage'>";
            echo "<img src='Icons/arrow_left_index.png' alt='' id='left_nav'>";
            echo "</a>";
        }

        // Generate both arrows.
        else {
            echo "<a href='index.php?page=$nextPage'>";
            echo "<img src='Icons/arrow_right_index.png' alt='' id='right_nav'>";
            echo "</a>";
            echo "<a href='index.php?page=$previousPage'>";
            echo "<img src='Icons/arrow_left_index.png' alt='' id='left_nav'>";
            echo "</a>";
        }
    }
}

/**
 * Removes the poll record from the DB if the user is authorized.
 * 
 * @param $uid Id of the user trying to remove the poll.
 * @param $pid Id of the poll to be removed.
 * 
 * @return void
 */
function removePoll($uid, $pid){
    include_once "db_connection.php";
    include_once "validation_include.php";
    if (isCreator($uid, $pid, $connection) or isAdmin($uid, $connection)){
        $query = "DELETE FROM polls WHERE id=?";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $query)){
            die("stmt");
        }
        mysqli_stmt_bind_param($stmt, "s", $pid);
        mysqli_stmt_execute($stmt);
    }
}

/**
 * Adds a poll record into the DB.
 * 
 * @param $answers Array of answers in the poll.
 * @param $question Question of the poll.
 * @param $createdBy Id of the user creating the poll.
 * 
 * @return void
 */
function addPoll($answers, $question, $createdBy){
    require "db_connection.php";
    // The query is dynamically generated based on the amount of answers submitted.
    $query = "INSERT INTO polls (dateAdded, createdBy, question";
    $answersCtr = 0;
    $fString = "sis";
    $answersFiltered = array();

    // Add the content of all nonempty answers field to the answersFiltered variable.
    // If the answer field is not empty, add the answer to the SQL query and to the STMT format string. 
    foreach($answers as $answer){
        if (!empty($answer)){
            $answersCtr += 1;
            $query .= ",Answer$answersCtr";
            $fString .= "s";
            $answersFiltered[] = $answer;
        }
    }

    // Finish the query by adding the correct amount of question marks based on the number of submitted answers.
    $query .= ") VALUES (?, ?, ?, ?";

    for ($i = 1; $i < $answersCtr; $i++){
        $query .= ",?";
    }

    $query .= ")";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
    }

    $date = date("Y-m-d");
    $createdBy = (int)$createdBy;

    mysqli_stmt_bind_param($stmt, $fString, $date, $createdBy, $question, ...$answersFiltered);
    mysqli_stmt_execute($stmt);
}

/**
 * Generates a form for creating a new poll. 
 * 
 * The links on the images are removed if JS is on. If the client has JS turned on, then this function
 * is only used to initialize the form, but adding new answer fields is handled on the client side.
 * 
 * @param $answers Number of text fields for answers to be generated.
 * 
 * @return void
 */
function generatePollInput($answers){
    if (!is_numeric($answers)){
        $answers = 2;
    }

    if ($answers < 3){
        $answers = 2;
        $decrementedAnswers = 2;
    }

    else if ($answers > 20){
        $answers = 20;
        $incrementedAnswers = 20;
    }

    // Echo the form.
    echo "<h1>Create a new poll</h1>";
    echo "<form action='php/new_poll_include.php' method='POST' name='newpoll'>";
    echo "<label for='question'>Question</label><br>";
    echo "<input type='text' name='question' id='question'";
    // Restore the the question input from user.
    if (isset($_SESSION["question"])){
        $question = htmlspecialchars($_SESSION["question"], ENT_QUOTES);
        echo " value='$question'";
    }
    echo "><br>";
    for ($i = 0; $i < $answers; $i++){
        $answerNumber = $i + 1;
        echo "<label for='answer$answerNumber'>Answer $answerNumber</label><br>";
        echo "<input type='text' name='answers[]' id='answer$answerNumber'";
        if (isset($_SESSION["answer".($i + 1)])){
            $answer = htmlspecialchars($_SESSION["answer".($i + 1)], ENT_QUOTES);
            echo " value='$answer'";
        }
        echo "><br>";
    }
    if (!isset($incrementedAnswers)){
        $incrementedAnswers = $answers + 1;
    }

    if(!isset($decrementedAnswers)){
        $decrementedAnswers = $answers - 1;
    }
    // Echo the plus and minus signs used to add and remove answer text fields.
    echo "<a href='new_poll.php?answers=$incrementedAnswers' id='plusphp'>";
    echo "<img src='Icons/plus_icon.png' alt='add another answer' id='addAnswer'>";
    echo "</a>";
    echo "<a href='new_poll.php?answers=$decrementedAnswers' id='minusphp'>";
    echo "<img src='Icons/minus_icon.png' alt='remove an answer' id='removeAnswer'><br>";
    echo "</a>";
    echo "<input type='submit' name='submit' value='Create a new poll'><br>";
    echo "</form>";
    echo "<script src='scripts/new_poll.js'></script>";
}

/**
 * Generates a form for voting in a poll. 
 * 
 * @param array $row Contains information from the DB about the poll.
 * @param $pid Id of the poll.
 * 
 * @return void
 */
function generateVotingForm($row, $pid){
    $i = 1;
    if (!$row){
        echo "Poll doesn't exist.";
        die();
    }
    echo "<form id='voting_form' action='php/vote_include.php' method='POST'>";
    // Iterate over all possible answers to the poll and generate radio buttons.
    while ($i != 21 && $row["Answer$i"] != NULL){
        $answerText = htmlspecialchars($row["Answer$i"], ENT_QUOTES);
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

/**
 * Calls the functions that generate the poll page.
 * 
 * @param $pid Id of the poll.
 * 
 * @return void
 */
function generatePoll($pid){
    $row = getPollRow($pid);
    if (!$row){
        echo "<p>This poll doesn't exist.</p>";
        exit();
    }
    generatePollDescription($row);
    generatepollResults($row);
    echo "<div id='piechart'></div>";
    if (isset($_GET["error"])) echo getError($_GET["error"]);
    generateVotingForm($row, $pid);
}

/**
 * Generates poll results in plain HTML. It get replaced by a pie chart if JS is enabled.
 * 
 * @param array $row Contains information from the DB about the poll.
 * 
 * @return void
 */
function generatePollResults($row){
    echo "<div id='jsoff'><ul>";
    for ($i = 1; $i <= 20; $i++){
        if (is_null($row["Answer$i"])){
            break;
        }
        $answer = htmlspecialchars($row["Answer$i"], ENT_QUOTES);
        $votes = $row["Votes$i"];
        echo "<li>$answer : $votes</li>";
    }
    echo "</ul></div>";
}

/**
 * Generates poll description HTML.
 * 
 * It echoes creator's name, question, buttons to delete the poll and block the creator.
 * 
 * @param array $row Contains information from the DB about the poll.
 * 
 * @return void
 */
function generatePollDescription($row){
    include_once "validation_include.php";
    include "db_connection.php";
    $question = htmlspecialchars($row["question"], ENT_QUOTES);
    $pid = $row["id"];
    $createdBy = htmlspecialchars(uidToUsername($row["createdBy"]), ENT_QUOTES);
    echo "<h1>$question</h1>";
    echo "<h2 id='poll_created_by'>Created by: $createdBy</h2>";
    // Generate delete button if user is the owner or an admin.
    if (isset($_SESSION["id"]) && (($_SESSION["id"] == $row["createdBy"]) || isAdmin($_SESSION["id"], $connection))){
        echo "<form action='php/delete_poll_include.php' method='post' class='poll_site_delete'>";
        echo "<input type='hidden' name='pid' value='$pid'>";
        echo "<input type='submit' name='submit' value='Delete the poll'>";
        echo "</form>";
    }
    // Generate block user button if user is an admin.
    if (isset($_SESSION["id"]) && isAdmin($_SESSION["id"], $connection)){
        $uid = $row["createdBy"];
        echo "<form action='php/block_user_include.php' method='post' class='poll_site_delete'>";
        echo "<input type='hidden' name='uid' value='$uid'>";
        echo "<input type='submit' name='submit' value='Block the creator of this poll'>";
        echo "</form>";
    }
}

/**
 * Fetches data from the DB based on a poll id.
 * 
 * @param $pid Id of the poll.
 * 
 * @return array
 */
function getPollRow($pid){
    // Returns row from poll table if found.
    require "db_connection.php";
    if (!is_numeric($pid)){
        return false;
    }
    $query = "SELECT * FROM polls WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
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

/**
 * Increments poll answer counter in the DB.
 * 
 * @param $pid Id of the poll.
 * @param $answerId Id of the answer, which was chosen.
 * @param $uid Id of the user trying to vote.
 * 
 * @return void
 */
function vote($pid, $answerId, $uid){
    require "db_connection.php";
    $pid = (int)$pid;
    $query = "UPDATE polls SET Votes$answerId = Votes$answerId + 1 WHERE id = $pid";
    $uid = mysqli_real_escape_string($connection, $uid);
    $answerId = mysqli_real_escape_string($connection, $answerId);
    $pid = mysqli_real_escape_string($connection, $pid);
    mysqli_query($connection, $query);
    updateVotes($pid, $uid);
}

/**
 * Inserts a record in the DB, that user has already voted in a certain poll to prevent repeated voting.
 * 
 * @param $pid Id of the poll.
 * @param $uid Id of the user trying to vote.
 * 
 * @return void
 */
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

/**
 * Echoes data about a poll in the JSON format for the Google charts plugin.
 * 
 * @param $pid Id of the poll.
 * 
 * @return void
 */
function getGoogleChartData($pid){
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

/**
 * Converts a user id to his/her username.
 * 
 * @param $uid Id of the user.
 * 
 * @return string
 */
function uidToUsername($uid){
    require "db_connection.php";
    $query = "SELECT username FROM users WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
    }
    $uid = (int)$uid;
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_array($result)["username"];
}