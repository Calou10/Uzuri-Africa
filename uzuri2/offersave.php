<?php
// db.php - MySQL connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "uzuri"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buyer_id = $_POST['buyer_id'];
    $seller_id = $_POST['seller_id'];
    $original_offer = $_POST['original_offer'];
    $counter_offer = $_POST['counter_offer'];

    // Insert the offer into the database
    $sql = "INSERT INTO offers (original_offer, counter_offer, buyer_id, seller_id) 
            VALUES ('$original_offer', '$counter_offer', '$buyer_id', '$seller_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Offer saved successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>

