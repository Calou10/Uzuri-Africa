<?php
// Database connection
$host = 'localhost';
$db = 'uzuri';  // Replace with your database name
$user = 'root';  // Replace with your database username
$password = '';  // Replace with your database password

// Create connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if data is being sent via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from POST request
    $buyer_id = $_POST['buyer_id'];
    $product_id = $_POST['product_id'];
    $bid_amount = $_POST['bid_amount'];

    // Validate inputs
    if (is_numeric($bid_amount) && $bid_amount > 0) {
        // Insert the bid into the database
        $stmt = $conn->prepare("INSERT INTO bids (buyer_id, product_id, bid_amount) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $buyer_id, $product_id, $bid_amount);

        if ($stmt->execute()) {
            echo "Your bid of €" . $bid_amount . " has been placed successfully!";
        } else {
            echo "Error placing bid: " . $conn->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Invalid bid amount!";
    }
}

// Close connection
$conn->close();
?>
