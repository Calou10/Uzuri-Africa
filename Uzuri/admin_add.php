<?php
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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $admin_notes = $_POST['admin_notes'];
    $role = 'admin';  // Since the user is a seller

    // Prepare the SQL query to insert the seller data into the database
    $stmt = $conn->prepare("INSERT INTO users (business_email, password, role, admin_notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $password, $role, $admin_notes);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to login page after successful registration
        header("Location: admin_login.html");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
