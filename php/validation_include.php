<?php
/**
 * Returns true if length of at least one of the parameters is 0. Otherwise returns false.
 * 
 * @param string $username Username
 * @param string $password Password
 * @param string $rpassword Password again
 * @param string $email Email
 * 
 * @return boolean
 */
function isEmpty($username, $password, $rpassword, $email){
    if (empty($username) or empty($password) or empty($rpassword) or empty($email)){
        return true;
    }

    return false;
}

/**
 * Returns true if a username is in a incorrect format.
 * 
 * It has to be 3 - 30 characters long and can only contain digits and letters in order
 * to be considered correct.
 * 
 * @param string $username Username
 * 
 * @return boolean
 */
function usernameWrong($username){
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

/**
 * Returns true if a password is in a incorrect format.
 * 
 * It has to be 6 - 100 characters long in order to be considered correct.
 * 
 * @param string $password password
 * 
 * @return boolean
 */
function passwordWrong($password){
    if (strlen($password) < 6 or strlen($password) > 100){
        return true;
    }
    
    return false;
    
}

/**
 * Returns true if passwords don't match.
 * 
 * @param string $password password
 * @param string $rpassword password again
 * 
 * @return boolean
 */
function dontMatch($password, $rpassword){
    if ($password !== $rpassword){
        return true;
    }

    return false;

}

/**
 * Returns true if email is in a incorrect format.
 * 
 * Uses the built in filter_var function.
 * 
 * @param string $email email
 * 
 * @return boolean
 */
function emailWrong($email){
    if (filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }

    return true;
}

/**
 * Returns true if a username is already in the DB.
 * 
 * @param string $username username
 * @param object $connection DB connection
 * 
 * @return boolean
 */
function usernameExists($username, $connection){
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

/**
 * Returns true if an email is already in the DB.
 * 
 * @param string $email email
 * @param object $connection DB connection
 * 
 * @return boolean
 */
function emailExists($email, $connection){
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

/**
 * Inserts a new user record into the DB. Encrypts the password using bcrypt.
 * 
 * @param string $username username
 * @param string $password password
 * @param string $email email
 * @param object $connection DB connection
 * 
 * @return void
 */
function createUser($username, $password, $email, $connection){
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

/**
 * Logs in the user (starts a new session with his uid) if correct credentials are entered.
 * 
 * @param string $username username
 * @param string $password password
 * @param object $connection DB connection
 * 
 * @return void
 */
function login($username, $password, $connection){
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

/**
 * Queries the DB if user has admin privileges.
 * 
 * @param $id user id
 * @param object $connection DB connection
 * 
 * @return boolean
 */
function isAdmin($id, $connection){
    $query = "SELECT admin FROM users WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
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

/**
 * Returns true if a user is the creator of a given poll.
 * 
 * @param $uid uid
 * @param $pid pid
 * @param object $connection connection
 * 
 * @return boolean
 */
function isCreator($uid, $pid, $connection){
    $query = "SELECT createdBy FROM polls where id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "s", $pid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    return ($row["createdBy"] == $uid);
}

/**
 * Returns true if a user has already voted.
 * 
 * @param $uid user id
 * @param $pid poll id
 * @param object $connection DB connection
 * 
 * @return boolean
 */
function userVoted($uid, $pid, $connection){
    $query = "SELECT * FROM votes WHERE uid = ? AND pid = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "ss", $uid, $pid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return (mysqli_num_rows($result) > 0);
}

/**
 * Returns true if user is blocked
 * 
 * @param $uid user id
 * @param object $connection DB connection
 * 
 * @return boolean
 */
function isBlocked($uid, $connection){
    $query = "SELECT * FROM users WHERE id = ? AND blocked = 1";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return (mysqli_num_rows($result) == 1);
}

/**
 * Blocks the user and removes all poll created by him/her.
 * 
 * @param $uid user id
 * @param object $connection DB connection
 * 
 * @return void
 */
function blockUser($uid, $connection){
    $query = "UPDATE users SET blocked = 1 WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    
    // Removes all polls created by the user.
    $query = "DELETE FROM polls WHERE createdBy = ?";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $query)){
        die("stmt");
    }
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
}