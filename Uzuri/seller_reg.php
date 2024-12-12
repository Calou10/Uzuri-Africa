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
    $company_name = $_POST['company_name'];
    $product = $_POST['product'];
    $business_address = $_POST['business_address'];
    $role = 'seller';  // Since the user is a seller

    // Prepare the SQL query to insert the seller data into the database
    $stmt = $conn->prepare("INSERT INTO users (business_email, password, role, company_name, product,  business_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $email, $password, $role, $company_name, $product, $business_address);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to login page after successful registration
        header("Location: seller_login.html");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
