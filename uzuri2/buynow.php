<?php
// Assuming you have a database connection
$servername = "localhost";
$username = "root";
$password = "";  // Adjust if you have a password
$dbname = "uzuri";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from POST request
$itemId = $_POST['item_id'];
$price = $_POST['price'];
$buyerId = 1;  // Replace with the actual buyer ID, maybe from session or user login

// Insert the item into the cart
$sql = "INSERT INTO cart (buyer_id, item_id, price) VALUES ('$buyerId', '$itemId', '$price')";

if ($conn->query($sql) === TRUE) {
    // Successfully added to cart
    echo json_encode(['success' => true]);
} else {
    // Error adding to cart
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

// Close connection
$conn->close();
?>
