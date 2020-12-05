<?php

require "db_connection.php";
include "polls_include.php";

$page = (int)$_GET["page"];
$skip = ($page - 1) * 25;
$query = "SELECT id, question, dateAdded, createdBy FROM polls ORDER BY id DESC LIMIT $skip, 25";
$result = mysqli_query($connection, $query);
echo "<tr>";
echo "<th>Poll name</th>";
echo "<th>Date</th>";
echo "<th>Created by</th>";
echo "</tr>";
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