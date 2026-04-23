<?php
session_start();
session_destroy();

header("Location: landing3.html");
exit();
?>
