<?php
session_start();
session_destroy();
header("Location: input002.php");
exit;
?>
