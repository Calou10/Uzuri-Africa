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
// Fetch category and type from query parameters
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$product_kind = isset($_GET['product_kind']) ? $_GET['product_kind'] : 'all';

// Build SQL query based on filters
$sql = "SELECT id, name, image_path, category, type, product_kind, price FROM items WHERE 1";

// Apply filters if needed
if ($category !== 'all') {
    $sql .= " AND category = '$category'";
}
if ($type !== 'all') {
    $sql .= " AND type = '$type'";
}

if ($product_kind !== 'all') {
    $sql .= " AND product_kind = '$product_kind'";
}

$result = $conn->query($sql);

// Fetch and display items
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='item-card'>";
        echo "<a href='view_itemdets.php?id=" . $row['id'] . "'>";
        echo "<img src='./" . $row['image_path'] . "' alt='" . $row['name'] . "'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>Category: " . $row['category'] . "</p>";
        echo "<p>Type: " . $row['type'] . "</p>";
        echo "<p>Product: " . $row['product_kind'] . "</p>";
        echo "<p>Price: €" . $row['price'] . "</p>";
        echo "</a>";
        echo "</div>";
    } 
} else {
    echo "<p>No items found.</p>";
}


?>
