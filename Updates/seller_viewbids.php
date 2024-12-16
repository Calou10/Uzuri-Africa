<?php
//print_r($_POST);
//print_r($_FILES);
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
//echo "Role: " . $_SESSION['role'];  // Output the role to see if it's set correctly
//echo "User ID: " . $_SESSION['user_id'];  // Output the user ID to see if it's set

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header("Location: seller_login.html"); // Redirect to login if not a seller
    exit();
}

$seller_id = $_SESSION['user_id']; // The seller's ID

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

// Fetch all bids with required details
$sql = "
    SELECT 
        bids.bid_id AS bid_id,
        buyers.email AS buyer_email,
        items.name AS item_name,
        items.image_path AS item_image,
        bids.bid_amount,
        bids.created_at,
        bids.buyer_id
    FROM 
        bids
    INNER JOIN buyers ON bids.buyer_id = buyers.id
    INNER JOIN items ON bids.product_id = items.id
    ORDER BY bids.created_at DESC
";
$result = $conn->query($sql);

// Handle bid actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bid_action'], $_POST['bid_id'], $_POST['buyer_id'])) {
    $bid_id = intval($_POST['bid_id']);
    $buyer_id = intval($_POST['buyer_id']);
    $action = $_POST['bid_action']; // Either "accept" or "reject"
    $message = '';

    if ($action === 'accept') {
        $message = "Your bid has been accepted!";
    } elseif ($action === 'reject') {
        $message = "Your bid has been rejected.";
    }

    if ($message !== '') {
        // Insert notification into the database
        $notificationStmt = $conn->prepare("INSERT INTO notifications (buyer_id, message, created_at) VALUES (?, ?, NOW())");
        $notificationStmt->bind_param("is", $buyer_id, $message);

        if ($notificationStmt->execute()) {
            echo "<script>alert('Bid $action successful!');</script>";
        } else {
            echo "<script>alert('Error sending notification.');</script>";
        }

        $notificationStmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bids</title>
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

        .container {
            width: 90%;
            margin: 20px auto;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        img {
            max-width: 100px;
            max-height: 100px;
        }

        .action-buttons form {
            display: inline-block;
        }

        .accept-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .reject-btn {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .accept-btn:hover, .reject-btn:hover {
            opacity: 0.8;
        }

        footer {
            margin-top: 593px;
            background-color: #068b50;
            color: white;
            text-align: center;
            font-size: 1rem;
            width: 100%;
            padding: 20px 30px;
            
        }

        footer a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #f52a23;
        }

        footer p {
            margin: 5px 0;
        }
        /* Responsive Design */
        @media (max-width: 768px) {

            header {
                flex-direction: column; /* Stack logo, h1, and nav vertically */
                align-items: center;
                text-align: center;
            }

            .logo img {
                width: 60px; /* Smaller logo for smaller screens */
                margin: 5px 0;
            }

            header h1 {
                font-size: 1rem; /* Reduced heading size for smaller screens */
                margin: 5px 0;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px;
            }
        
            h1 {
                font-size: 2rem;
            }
            
            h2 {
                font-size: 1.6rem;
            }

            input[type="text"], input[type="number"], select, button[type="submit"] {
                font-size: 1rem;
            }

            .form-container {
                padding: 15px;
                flex-direction: column;
                width: 300px;
            }

            .form2-container {
                padding: 15px;
                flex-direction: column;
                width: 300px;
            }

                     th, td {
                padding: 10px;
            }

            table {
                font-size: 0.9rem;
            }

            form input, form textarea {
                font-size: 1rem;
            }
        

        footer {
                font-size: 0.9rem;
                padding: 15px 5px;
                margin-top: auto;
            }
        }

            

        @media (max-width: 480px) {

            header h1 {
                font-size: 0.9rem;
            }

            nav ul li a {
                font-size: 0.8rem;
            }
        
            input[type="text"], input[type="number"], select, textarea, button[type="submit"] {
                font-size: 0.9rem;
            }

             .form-container {
                padding: 15px;
                flex-direction: column;
                width: 150px;
            }

            .form2-container {
                padding: 15px;
                flex-direction: column;
                width: 150px;
            }
        

            table {
                font-size: 0.8rem;
            }
            
            th, td {
                padding: 8px;
            }

            form input, form textarea {
                font-size: 0.9rem;
            }

            form button {
                font-size: 1rem;
            }
        

            footer {
                font-size: 0.8rem;
                padding: 10px 5px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/UZURI AFRICA2.jpg" alt="Uzuri Africa Logo">
        </div>
        <h2>ALL BIDS</h2>
        <nav>
            <ul>
                <li><a href="buyer_dashboard.php">Home</a></li>
                <li><a href="seller_login.html"><?php echo htmlspecialchars($email); ?><p>(logout)</a></li>
                
            </ul>
        </nav>
        
    </header>
    <div class="container">
        
        <table>
            <thead>
                <tr>
                    <th>Bid ID</th>
                    <th>Buyer Email</th>
                    <th>Item Name</th>
                    <th>Image</th>
                    <th>Bid Amount (€)</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['bid_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['buyer_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td>
                                <?php if (!empty($row['item_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['item_image']); ?>" alt="Item Image">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['bid_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td class="action-buttons">
                                <form method="POST">
                                    <input type="hidden" name="bid_id" value="<?php echo $row['bid_id']; ?>">
                                    <input type="hidden" name="buyer_id" value="<?php echo $row['buyer_id']; ?>">
                                    <button type="submit" name="bid_action" value="accept" class="accept-btn">Accept</button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="bid_id" value="<?php echo $row['bid_id']; ?>">
                                    <input type="hidden" name="buyer_id" value="<?php echo $row['buyer_id']; ?>">
                                    <button type="submit" name="bid_action" value="reject" class="reject-btn">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No bids found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy; 2024 Uzuri Africa. All rights reserved.</p>
    </footer>

</body>
</html>

<?php
$conn->close();
?>
