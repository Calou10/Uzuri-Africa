<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uzuri";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $dob = trim($_POST['dob']);

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit;
    }
    if (strlen($password) < 6) {
        echo "Password must be at least 6 characters long.";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO buyers (fname, lname, email, password1, address, phone, dob) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fname, $lname, $email, $hashedPassword, $address, $phone, $dob);

    if ($stmt->execute()) {
        echo "success"; // Indicate success with a simple keyword
    } else {
        if ($conn->errno == 1062) { // Duplicate entry
            echo "Email is already registered.";
        } else {
            echo "Database error: " . $conn->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>
