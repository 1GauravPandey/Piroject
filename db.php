<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Change this only if your MySQL has a password
$db = 'jutta_sansaar1';

// Establish connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
