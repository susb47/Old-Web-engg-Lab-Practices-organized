<?php
$servername = "localhost";  // Default XAMPP server
$username = "root";         // Default MySQL username
$password = "";             // Default password (empty on XAMPP)
$dbname = "curd_db";        // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!<br>";
}
?>