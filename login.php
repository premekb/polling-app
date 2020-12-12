<?php
    include_once "header.php";
    include "php/errors_include.php";
?>
    <main>
        <h1>Log in</h1>
        <form action="php/login_include.php" method="post" name="login">
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username" value="<?php if (isset($_SESSION["l_username"])) {echo htmlspecialchars($_SESSION["l_username"], ENT_QUOTES);} ?>"><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password"><br>
            <input type="submit" value="Login" name="submit">
        </form>
        <script src="scripts/login.js"></script>
        <?php if (isset($_GET["error"])) echo getError($_GET["error"]);?>
    </main>
</body>
</html>