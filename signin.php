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

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input values
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate email and password
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    if (empty($password)) {
        die("Password cannot be empty.");
    }

    // Query the database for user credentials
    $stmt = $conn->prepare("SELECT id, password1 FROM buyers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $dbPassword);

    // Check if user exists and password matches
   //if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
        // Redirect to purchases page directly if credentials are valid
    if ($stmt->fetch()) {
        // Debugging: Output the database password for verification
        echo "Database password (hashed): " . $dbPassword . "<br>";
        echo "Provided password: " . $password . "<br>";

        // Check if the passwords match
        if (password_verify($password, $dbPassword)) {
            header("Location: index.html");
            exit;
        } else {
            echo "Invalid email or password.";
        }
    } else {
    echo "No matching email.";
    }


    $stmt->close();

}


$conn->close();
?>
