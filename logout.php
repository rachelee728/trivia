<?php
setcookie('type', '', time() - 3600);
setcookie("score", 0, time() -3600, "/");
header("Location:index.php");