<?php
/**
 * This file logs out the user.
 */
session_start();
session_unset();
session_destroy();
header("location: ../index.php");
exit();