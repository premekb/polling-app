<?php
include "header.php";

if (isset($_SESSION["id"]) && isAdmin($_SESSION["id"], $connection)){
    echo "<main>";
    echo "<form action='php/login_include.php' method='post' name='delete_user'>";
    echo "<label for='username'>Username</label><br>";
    echo "<input type='text' id='username' name='username'><br>";
    echo "<input type='submit' value='Delete user' name='submit_username'>";
    echo "</form>";
    echo "<br>";
    echo "<form action='php/login_include.php' method='post' name='delete_poll'>";
    echo "<label for='pid'>Poll id</label><br>";
    echo "<input type='text' id='pid_admin' name='pid_admin'><br>";
    echo "<input type='submit' value='Delete poll' name='submit_pid'>";
    echo "</form>";
    echo "</main>";
    echo "<script src='scripts/admin.js' defer></script>";
    echo "TODO EVERYTHING";
}

else{
    die("Access forbidden");
}
?>
    </body>
</html>

