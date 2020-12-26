<?php
    include_once "header.php";
    include "php/polls_include.php";
?>

    <main>
            <?php
                if (isset($_GET["page"])){
                    generatePollTable($_GET["page"]);
                }

                else {
                    $_GET["page"] = 1;
                    generatePollTable($_GET["page"]);
                }

            ?>
    </main>

    <script src="scripts/row_select.js" defer></script>
    <script src="scripts/index_table_ajax.js" defer></script>
    
    <nav id="bottom_nav">
    <?php printArrows($_GET["page"]); ?>
    <form action="index.php" method="GET" name="pages">
                <span id="span_bottom_nav">
                <input type="submit" id="gotopage" name="gotopage" value="Go to">
                <label for="page" id="pagelabel">page</label>
                <input type="number" name="page" id="page" value=<?php echo htmlspecialchars($_GET["page"], ENT_QUOTES);?> min="1" max=<?php echo getPages(); ?>> <span id="of_pages">of <?php echo getPages();?></span>
            </span>
    </form>
    </nav>
</body>
</html>

