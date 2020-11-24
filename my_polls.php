<?php
    include_once "header.php";
    include "php/polls_include.php";
?>
    <article>
        <?php
        if (isset($_SESSION["id"])){
            generateUserPollTable(1, 1);
        }
        else{
            echo "<p>You need to login to acces this page.</p>";
        }
        ?>
    </article>
</body>
</html>

