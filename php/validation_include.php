<?php
function isEmpty($username, $password, $rpassword, $email){
    // Nektery field mi poslal user prazdny
    if (empty($username) or empty($password) or empty($rpassword) or empty($email)){
        return true;
    }

    return false;
}

function usernameWrong($username){
    // Username ve spatnem formatu
    if (strlen($username) < 3 or strlen($username) > 30){
        return true;
    }

    for ($i = 0; $i < strlen($username); $i++){
        if ((!ctype_digit($username[$i])) && (!ctype_alpha($username[$i]))){
            return true;
        }
    }

    return false;
}

function passwordWrong($password){
    // Password ve spatnem formatu
    if (strlen($password) < 6 or strlen($password) > 100){
        return true;
    }
    
    return false;
    
}

function dontMatch($password, $rpassword){
    // Passwords se neshoduji
    if ($password !== $rpassword){
        return true;
    }

    return false;

}

function emailWrong($email){
    // Email ve spatnem formatu
    if (filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }

    return true;
}

function usernameExists($username, $connection){
    // Username is already in the database.
    $query = "SELECT username FROM users WHERE username=?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        header("location: ../register.php?error=stmt");
        exit();
    }

    else{
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result)){
            return true; 
        }
    }
    return false;
}

function emailExists($email, $connection){
    // Email is already in the database.
    $query = "SELECT email FROM users WHERE email=?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        header("location: ../register.php?error=stmt");
        exit();
    }

    else{
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result)){
            return true; 
        }
    }
    return false;
}

function createUser($username, $password, $email, $connection){
    // Register user
    $password = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        header("location: ../register.php?error=stmt");
        exit();
    }

    else{
        mysqli_stmt_bind_param($stmt, "sss", $username, $password, $email);
        mysqli_stmt_execute($stmt);
    }
    
    header("location: ../register.php?error=usercreated");
}

function login($username, $password, $connection){
    // Logs in the user, if correct credentials are entered.
    $query = "SELECT id, password FROM users WHERE username=?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        header("location: ../login.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $query_result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($query_result);

    if (mysqli_num_rows($query_result) == 1 and password_verify($password, $row["password"])){
        session_start();
        $_SESSION["id"] = $row["id"];
        unset($_SESSION["l_username"]);
        header("location: ../index.php");
        exit();
    }

    else {
        // Session variable to return the data back to the failed form.
        session_start();
        $_SESSION["l_username"] = $username;
        header("location: ../login.php?error=loginfailed");
        exit();
    }
}

function isAdmin($id, $connection){
    //Returns true if user is an admin.
    $query = "SELECT admin FROM users WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        // pak prepis na normal error
        echo("STMT");
        die();
    }
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    
    // Returns false if no row is found.
    if (!isset($row["admin"])){
        return False;
    }

    return ($row["admin"] == 1);
}

function isCreator($uid, $pid, $connection){
    // Returns true if user is the creator of a poll.
    $query = "SELECT createdBy FROM polls where id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        // Zmen mozna potom error
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "s", $pid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    return ($row["createdBy"] == $uid);
}

function userVoted($uid, $pid, $connection){
    // Returns true if user has already voted. (NOT TESTED YET!)
    $query = "SELECT * FROM votes WHERE uid = ? AND pid = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        // Zmen mozna potom error
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "ss", $uid, $pid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return (mysqli_num_rows($result) > 0);
}

function isBlocked($uid, $connection){
    // Returns true if user is blocked.
    $query = "SELECT * FROM users WHERE id = ? AND blocked = 1";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        // Zmen mozna potom error
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return (mysqli_num_rows($result) == 1);
}

function blockUser($uid, $connection){
    // Blocks the user.
    $query = "UPDATE users SET blocked = 1 WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        // Zmen mozna potom error
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    
    // Removes all polls created by the user.
    $query = "DELETE FROM polls WHERE createdBy = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        // Zmen mozna potom error
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
}