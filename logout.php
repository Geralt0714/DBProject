<?php
session_start();
$_SESSION = array();

session_destroy();

echo "<h1>You have successfully logged out</h1>";
echo "<a href=\"http://localhost:8080/login.html\">Go to login page</a>";
?>