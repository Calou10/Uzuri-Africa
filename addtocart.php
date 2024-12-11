<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";  // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Retrieve item data from POST request
$itemId = $_POST['item_id'];
$price = $_POST['price'];
$quantity = $_POST['quantity'] ?? 1;  // Default quantity is 1

$buyerId = $_SESSION['user_id'];  // Buyer ID from session

// Validate item existence (this step depends on your database structure)
$sql = "SELECT * FROM items WHERE id = $itemId";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Item does not exist']);
    exit;
}

// Insert item into the cart
$sql = "INSERT INTO cart (buyer_id, item_id, price, quantity) VALUES ('$buyerId', '$itemId', '$price', '$quantity')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Item added to cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding to cart: ' . $conn->error]);
}

$conn->close();
?>
