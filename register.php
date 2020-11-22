<?php
    include_once "header.php";
    include "php/errors_include.php";
?>

    <main>
        <h1>Create a new account</h1>
        <form action="php/register_include.php" method="post" name="register">
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username"><br>
            <label for="email">E-mail address</label><br>
            <input type="email" id="email" name="email"><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password"><br>
            <label for="r_password">Repeat your password</label><br>
            <input type="password" id="r_password" name="r_password"><br>
            <input type="submit" value="Register" name="submit" id="register">
        </form>
        <?php if (isset($_GET["error"])) echo printError($_GET["error"]); ?>
        <script src="scripts/register.js"></script>
    </main>
</body>
</html>