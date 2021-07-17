<?php
$server = "localhost";
$user = "expertsupport_eol3";
$pass = "Online_123456";
$dbname = "expertsupport_eol3";
$con = @mysql_connect($server, $user, $pass);
if (!$con) {
    die('Could not connect: ' . mysql_error()); 
}
$qqq = mysql_select_db($dbname, $con);
?>