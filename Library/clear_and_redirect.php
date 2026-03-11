<?php
session_start();
unset($_SESSION['borrow_reader_id']);
header("Location: borrow_input_reader.php?clear=1");
exit;
