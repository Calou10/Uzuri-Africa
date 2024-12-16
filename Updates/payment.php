<?php

//print_r($_POST);
//print_r($_FILES);
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
//echo "Role: " . $_SESSION['role'];  // Output the role to see if it's set correctly
//echo "User ID: " . $_SESSION['user_id'];  // Output the user ID to see if it's set

//if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
  //  header("Location: signin.html"); // Redirect to login if not a seller
    //exit();
//}
// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit();
}

// Access session variables
$user_id = $_SESSION['user_id'];
//$email = $_SESSION['email'];

$buyer_id = $_SESSION['user_id']; // The seller's ID

$email = isset($_SESSION['email']) ? $_SESSION['email'] : "Unknown";
// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "uzuri"; // Replace with your database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}



// Simulate some server-side payment logic
function validate_card($card_number, $expiry, $cvv) {
    // Basic validations
    if (strlen($card_number) !== 16 || !ctype_digit($card_number)) {
        return ['status' => 'failed', 'message' => 'Invalid card number.'];
    }

    if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
        return ['status' => 'failed', 'message' => 'Invalid expiry date.'];
    }

    if (strlen($cvv) !== 3 || !ctype_digit($cvv)) {
        return ['status' => 'failed', 'message' => 'Invalid CVV.'];
    }

    // Simulated card number rules
    if ($card_number === '4242424242424242') {
        return ['status' => 'success', 'message' => 'Payment successful!'];
    } elseif ($card_number === '4000000000000002') {
        return ['status' => 'failed', 'message' => 'Card declined.'];
    }

    return ['status' => 'failed', 'message' => 'Payment failed for unknown reasons.'];
}

// Handle POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $card_number = $_POST['card_number'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

     if (empty($name) || empty($card_number) || empty($expiry) || empty($cvv)) {
        echo json_encode(['status' => 'failed', 'message' => 'All fields are required.']);
        exit;
    }

    // Simulate payment validation
    $result = validate_card($card_number, $expiry, $cvv);

    // Return result as JSON (for AJAX or as a response to a POST form)
    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    echo json_encode(['status' => 'failed', 'message' => 'Invalid request method.']);
}
?>

?>