<?php
/**
 * This file handles the AJAX request for the table on the index page.
 */
include "polls_include.php";

generatePollTable((int)$_GET["page"], (int)$_GET["rows"]);