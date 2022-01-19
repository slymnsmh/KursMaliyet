<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "maliyetHesap";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('Europe/Istanbul');

?>