<?php
    include_once "header.php";
    require "php/polls_include.php";
?>

    <article>
        <?php
        if (isset($_SESSION["id"])){
            if (!isset($_GET["answers"])){
                $_GET["answers"] = 2;
            }
            generatePollInput($_GET["answers"]);
        }
        
        else{
            echo "<p>You need to login to create a new poll</p>";
        }
        ?>
    </article>
</body>
</html>