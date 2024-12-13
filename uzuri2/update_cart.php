<?php
session_start();

// Check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    die("ERROR: Buyer is not logged in.");
}

// Get the buyer ID from the session
$buyer_id = $_SESSION['buyer_id'];

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

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if cart_id and action are set
    if (!isset($_POST['cart_id'], $_POST['action'])) {
        die("ERROR: Invalid request.");
    }

    // Sanitize inputs
    $cart_id = intval($_POST['cart_id']);
    $action = $_POST['action'];

    // Validate inputs
    if ($cart_id <= 0 || !in_array($action, ['increase', 'decrease'])) {
        die("ERROR: Invalid request.");
    }

    // Fetch the current quantity for the given cart item
    $cartStmt = $conn->prepare("SELECT quantity FROM cart WHERE id = ? AND buyer_id = ?");
    if (!$cartStmt) {
        die("ERROR: Failed to prepare statement.");
    }
    $cartStmt->bind_param("ii", $cart_id, $buyer_id);
    $cartStmt->execute();
    $cartStmt->bind_result($currentQuantity);

    if ($cartStmt->fetch()) {
        $cartStmt->close(); // Close the statement after fetching the result

        // Determine the new quantity based on the action
        if ($action === 'increase') {
            $newQuantity = $currentQuantity + 1;
        } elseif ($action === 'decrease') {
            $newQuantity = $currentQuantity - 1;

            // Ensure quantity does not drop below 1
            if ($newQuantity < 1) {
                die("ERROR: Quantity cannot be less than 1.");
            }
        }

        // Update the cart with the new quantity
        $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND buyer_id = ?");
        if (!$updateStmt) {
            die("ERROR: Failed to prepare update statement.");
        }
        $updateStmt->bind_param("iii", $newQuantity, $cart_id, $buyer_id);

        if ($updateStmt->execute()) {
            echo "SUCCESS";
        } else {
            echo "ERROR: Failed to update quantity.";
        }
        $updateStmt->close(); // Close update statement
    } else {
        $cartStmt->close(); // Ensure cartStmt is closed if fetch fails
        echo "ERROR: Cart item not found.";
    }
} else {
    die("ERROR: Invalid request method.");
}

// Close the database connection
$conn->close();
?>
