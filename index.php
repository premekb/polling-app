<?php
    include_once "header.php";
    include "php/polls_include.php";
?>

    <article>
            <?php
                if (isset($_GET["page"])){
                    generatePollTable($_GET["page"]);
                }

                else {
                    $_GET["page"] = 1;
                    generatePollTable($_GET["page"]);
                }
            ?>
    </article>

    <script src="scripts/index_table_ajax.js" defer></script>
    
    <nav id="bottom_nav">
    <?php printArrows($_GET["page"]); ?>
    <form action="index.php" method="GET" name="pages">
                <span id="span_bottom_nav">
                <input type="submit" id="gotopage" name="gotopage" value="Go to">
                <label for="page" id="pagelabel">page</label>
                <input type="number" name="page" id="page" value=<?php echo $_GET["page"];?> min="1" max=<?php echo getPages(); ?>> of <?php echo getPages();?>
            </span>
    </form>
    </nav>

    <p>Na nekterych mistech v kodu porad jeste pouzivas mysqli_query, treba generovani tablu.</p>
    <p>Mozna to bude potreba reorganizovat a lisknout potom includes nad www folder</p>
    <p>Zmenit vsechny article na tag main</p>
    <p>Jestli validace dopadne spatne -> vygeneruj ten formular znova a hodnoty vypln.</p>
    <p>Create a new poll, zrob ajax aby kdyz to nekdo leavne a pak prijde zpatky aby mu pri mackani pluska a minuska skakaly ty odpovedi.</p>
</body>
</html>

