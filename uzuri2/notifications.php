<?php
session_start();

// Check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    die("ERROR: You must log in to view notifications.");
}

$buyer_id = $_SESSION['buyer_id'];

// Access session variables
$user_id = $_SESSION['user_id'];

$email = isset($_SESSION['email']) ? $_SESSION['email'] : "Unknown";
// Database connection details
$host = 'localhost';
$db = 'uzuri'; // Replace with your database name
$user = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Create connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch notifications with item names for the logged-in buyer
$sql = "
    SELECT notifications.id AS notification_id, notifications.message, notifications.created_at, items.name AS item_name, items.image_path AS item_image
    FROM notifications
    LEFT JOIN  bids ON notifications.id = bids.bid_id
    LEFT JOIN  items ON bids.product_id = items.id
    WHERE notifications.buyer_id = ?
    ORDER BY notifications.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notifications</title>
    <style>

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #EDE6CF;
            color: #333;
            justify-content: center;
        }
        header {
            position: sticky;
            top: 0;
            background-color: #068b50;
            color: white;
            padding: 10px 20px;
            z-index: 10;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo img {
            width: 100px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            text-decoration: none;
            font-weight: bold;
            color: white;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #f52a23;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        h1 {
            margin-top: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .empty-notifications {
            text-align: center;
            font-size: 1.2em;
            color: #888;
        }
    </style>
</head>
<body>
     <header>
        <div class="logo">
            <img src="images/UZURI AFRICA2.jpg" alt="Uzuri Africa Logo">
        </div>
        
        <nav>
            <ul>
                <li><a href="buyer_dashboard.php">Home</a></li>
                <li><a href="buyer_dashboard.php">Continue Shopping</a></li>
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="view_cart.php">Cart</a></li>
                <li><a href="index.html"><?php echo htmlspecialchars($email); ?><p>(logout)</a></li>
            </ul>
        </nav>
        
    </header>
    <div class="container">
        <h1>My Notifications</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Notification</th>
                        <th>Received At</th>
                    </tr>
                </thead>
                <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['item_name'] ?? 'Unknown Item'); ?></td>
                    <td><?php echo htmlspecialchars($row['message'] ?? 'No message available'); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at'] ?? 'Unknown date'); ?></td>
                </tr>
            <?php endwhile; ?>

                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-notifications">You have no notifications at the moment.</div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
