<?php
// Database connection details
$host = 'localhost';
$db = 'uzuri'; 
$user = 'root'; 
$password = ''; 

// Create connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bid_id = isset($_POST['bid_id']) ? intval($_POST['bid_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Validate inputs
    if ($bid_id <= 0 || !in_array($action, ['accept', 'reject'])) {
        die("Invalid action or bid ID.");
    }

    // Fetch the buyer's details
    $stmt = $conn->prepare("
        SELECT buyers.id AS buyer_id, buyers.email 
        FROM bids 
        INNER JOIN buyers ON bids.buyer_id = buyers.id 
        WHERE bids.id = ?
    ");
    $stmt->bind_param("i", $bid_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $buyer = $result->fetch_assoc();
    $stmt->close();

    if (!$buyer) {
        die("Bid not found.");
    }

    $buyer_id = $buyer['buyer_id'];
    $email = $buyer['email'];
    $message = "";

    // Handle accept or reject action
    if ($action === 'accept') {
        $message = "Your bid has been accepted.";
    } elseif ($action === 'reject') {
        $message = "Your bid has been rejected.";
    }

    // Insert notification into the database
    $notifStmt = $conn->prepare("INSERT INTO notifications (buyer_id, message) VALUES (?, ?)");
    $notifStmt->bind_param("is", $buyer_id, $message);
    if ($notifStmt->execute()) {
        echo "Notification sent: " . $message;
    } else {
        echo "Failed to send notification.";
    }
    $notifStmt->close();

    // Optionally delete the bid
    $deleteStmt = $conn->prepare("DELETE FROM bids WHERE id = ?");
    $deleteStmt->bind_param("i", $bid_id);
    $deleteStmt->execute();
    $deleteStmt->close();
}

// Close the connection
$conn->close();
?>
