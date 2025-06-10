<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "final_fyp";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}

?>