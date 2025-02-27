<?php
$servername = "localhost"; // XAMPP default server
$username = "root"; // Default XAMPP MySQL user
$password = ""; // Default is empty
$database = "vaidyahub"; // Your database name

// Create connection
$con = new mysqli($servername, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// echo "Connected successfully"; // Uncomment to test connection
?>
