<?php
/*
    Database connection file
    Uses default XAMPP settings: host=localhost, user=root, password=""
    Change these values if your setup is different.
*/

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "health_assistant";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Stop the program and show an error if connection fails
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
