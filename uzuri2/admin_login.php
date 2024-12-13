<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

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
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Query to check if the seller exists in the database
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE business_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $dbPassword, $role);
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User exists, check password
            if ($stmt->fetch()){
                // Debugging: Output the database password for verification
                echo "Database password (hashed): " . $dbPassword . "<br>";
                echo "Provided password: " . $password . "<br>";

            if (password_verify($password, $dbPassword) && $role === 'admin') {
                // Successful login, start session and redirect to seller dashboard
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email; // Store the email in session
                echo "Session email is set to: " . $_SESSION['email'];
                header("Location: admin_dashboard.php");
                exit;
            } else {
                echo "Invalid login credentials.";
            }
        }
    } else {
            echo "No user found with that email.";
    }

    $stmt->close();
}

?>
