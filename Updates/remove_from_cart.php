<?php
session_start();

// Check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    die("ERROR: Buyer is not logged in.");
}

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("ERROR: Invalid request.");
}

// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "uzuri"; // Replace with your database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("ERROR: Database connection failed: " . $conn->connect_error);
}

// Get the cart_id from the POST data
if (isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);

    // Delete the item from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);

    if ($stmt->execute()) {
        echo "SUCCESS";
    } else {
        echo "ERROR: Failed to remove item from cart.";
    }

    $stmt->close();
} else {
    echo "ERROR: Missing cart ID.";
}

$conn->close();
?>
