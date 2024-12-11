<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

$category = $_GET['category'] ?? 'all';
$type = $_GET['type'] ?? 'all';

$query = "SELECT * FROM items WHERE 
          ('$category' = 'all' OR category = '$category') AND 
          ('$type' = 'all' OR type = '$type')";

$result = $conn->query($query);
$items = [];
while ($row = $result->fetch_assoc()) {
    $row['image_path'] = "assets/images/" . basename($row['image_path']);
    $items[] = $row;
}

header('Content-Type: application/json');
echo json_encode($items, JSON_UNESCAPED_SLASHES);

$conn->close();
?>
