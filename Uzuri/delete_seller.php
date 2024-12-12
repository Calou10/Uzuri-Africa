<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "uzuri");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'original_email' is set and not empty
if (isset($_POST['business_email']) && !empty($_POST['business_email'])) {
    $email = $_POST['business_email'];

    // Prepare and bind the delete query to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM users WHERE business_email = ?");
    $stmt->bind_param("s", $email);

    // Execute the delete query
    if ($stmt->execute()) {
        // Redirect back to the delete page with a success message
        echo "<script>alert('Seller deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting seller. Please try again.'); window.location.href='admin_dashboard.php';</script>";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "<script>alert('No student email provided.'); window.location.href='admin_dashboard.php';</script>";
}

// Close the connection
$conn->close();
?>
