<?php
$host = 'localhost';
$user = 'root';
$password = '#Gwagwalada2024'; // Your MySQL password
$dbname = 'uzuri_africa';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
