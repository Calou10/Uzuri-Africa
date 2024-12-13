<?php
session_start();

// To check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    die(json_encode(["success" => false, "message" => "Buyer is not logged in."]));
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
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $item_price = isset($_POST['item_price']) ? floatval($_POST['item_price']) : 0; // Use 'item_price'
    $quantity = 1; // Default quantity is 1

    // Validate inputs
    if ($item_id <= 0 || $item_price <= 0) {
        die(json_encode(["success" => false, "message" => "Invalid item details."]));
    }

    // Insert or update the cart
    $stmt = $conn->prepare("INSERT INTO cart (buyer_id, item_id, price, quantity) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + 1, price = ?");
    $stmt->bind_param("iidid", $buyer_id, $item_id, $item_price, $quantity, $item_price);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Item added to cart successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add item to cart."]);
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
