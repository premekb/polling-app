<?php
include "header.php";

if (isset($_SESSION["id"]) && isAdmin($_SESSION["id"], $connection)){
    echo "<main>";
    echo "<form action='php/login_include.php' method='post' name='login'>";
    echo "<label for='username'>Username</label><br>";
    echo "<input type='text' id='username' name='username'><br>";
    echo "<input type='submit' value='Delete user' name='submit'>";
    echo "</form>";
    echo "<br>";
    echo "<form action='php/login_include.php' method='post' name='login'>";
    echo "<label for='username'>Poll id</label><br>";
    echo "<input type='text' id='username' name='username'><br>";
    echo "<input type='submit' value='Delete poll' name='submit'>";
    echo "</form>";
    echo "</main>";
    echo "TODO EVERYTHING";
}

else{
    die("Access forbidden");
}
?>
    </body>
</html>

