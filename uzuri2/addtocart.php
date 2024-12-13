<?php
session_start();

// To check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    die("ERROR: Buyer is not logged in.");
}

// Get the buyer ID from the session
$buyer_id = $_SESSION['buyer_id'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "uzuri";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("ERROR: Database connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $agreed_price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Validate inputs
    if ($item_id <= 0) {
        die("ERROR: Invalid product ID.");
    }

    if ($quantity <= 0) {
        die("ERROR: Quantity must be at least 1.");
    }

    // Add a new cart item
    $insertStmt = $conn->prepare("INSERT INTO cart (buyer_id, item_id, price, quantity) VALUES (?, ?, ?, ?)");
    $insertStmt->bind_param("iidi", $buyer_id, $item_id, $agreed_price, $quantity);

    if ($insertStmt->execute()) {
        echo "SUCCESS";
    } else {
        echo "ERROR: Failed to add the item to the cart.";
    }
    $insertStmt->close();
}

// Close the database connection
$conn->close();
?>
